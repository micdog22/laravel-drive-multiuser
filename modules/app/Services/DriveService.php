<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DriveService
{
    public function client(): Drive
    {
        $client = new GoogleClient();
        $client->setApplicationName(config('app.name'));
        $client->setScopes([config('services.google.scope', 'https://www.googleapis.com/auth/drive.file')]);
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->setRedirectUri(route('google.callback', absolute: true));
        $credPath = storage_path('app/google/credentials.json');
        $client->setAuthConfig($credPath);

        $uid = Auth::id();
        $tokensPath = storage_path("app/google/tokens/{$uid}.json");
        if (is_file($tokensPath)) {
            $accessToken = json_decode(file_get_contents($tokensPath), true);
            $client->setAccessToken($accessToken);
            if ($client->isAccessTokenExpired()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                if (!is_dir(dirname($tokensPath))) mkdir(dirname($tokensPath), 0775, true);
                file_put_contents($tokensPath, json_encode($client->getAccessToken()));
            }
        } else {
            throw new \RuntimeException('Falta token Google. Conecte sua conta.');
        }
        return new Drive($client);
    }

    public function authUrl(): string
    {
        $client = new GoogleClient();
        $client->setApplicationName(config('app.name'));
        $client->setScopes([config('services.google.scope', 'https://www.googleapis.com/auth/drive.file')]);
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->setRedirectUri(route('google.callback', absolute: true));
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        return $client->createAuthUrl();
    }

    public function storeToken(array $token): void
    {
        $uid = Auth::id();
        $path = storage_path("app/google/tokens/{$uid}.json");
        if (!is_dir(dirname($path))) mkdir(dirname($path), 0775, true);
        file_put_contents($path, json_encode($token));
    }

    public function ensureUserFolder(string $email): string
    {
        $service = $this->client();
        $q = sprintf("name = 'user: %s' and mimeType = 'application/vnd.google-apps.folder' and trashed = false", addslashes($email));
        $res = $service->files->listFiles(['q' => $q, 'fields' => 'files(id,name)']);
        if ($res->files && count($res->files) > 0) {
            return $res->files[0]->id;
        }
        $meta = new DriveFile(['name' => "user: {$email}", 'mimeType' => 'application/vnd.google-apps.folder']);
        $created = $service->files->create($meta, ['fields' => 'id']);
        return $created->id;
    }

    public function upload(array $file, string $email): array
    {
        $service = $this->client();
        $folder = $this->ensureUserFolder($email);
        $driveFile = new DriveFile(['name' => $file['name'], 'parents' => [$folder]]);
        $created = $service->files->create($driveFile, [
            'data' => file_get_contents($file['tmp_name']),
            'mimeType' => $file['type'] ?: 'application/octet-stream',
            'uploadType' => 'multipart',
            'fields' => 'id,name,size,webViewLink,webContentLink'
        ]);
        return [
            'id' => $created->id,
            'name' => $created->name,
            'size' => (int)($created->size ?? 0),
            'view' => $created->webViewLink ?? null,
            'download' => $created->webContentLink ?? null,
        ];
    }
}
