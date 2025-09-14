<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\FileUpload;
use App\Services\DriveService;

class DriveController extends Controller
{
    public function index()
    {
        $files = FileUpload::where('user_id', Auth::id())->latest()->get();
        return view('drive.index', compact('files'));
    }

    public function connect(DriveService $drive)
    {
        return redirect()->away($drive->authUrl());
    }

    public function callback(Request $request, DriveService $drive)
    {
        $code = $request->query('code');
        if (!$code) abort(400, 'code ausente');

        $client = new \Google\Client();
        $client->setApplicationName(config('app.name'));
        $client->setScopes([config('services.google.scope', 'https://www.googleapis.com/auth/drive.file')]);
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->setRedirectUri(route('google.callback', absolute: true));
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $token = $client->fetchAccessTokenWithAuthCode($code);
        if (isset($token['error'])) {
            abort(400, 'Erro OAuth: '.$token['error']);
        }
        $drive->storeToken($token);
        return redirect()->route('drive.index')->with('status', 'Google conectado com sucesso.');
    }

    public function upload(Request $request, DriveService $drive)
    {
        $request->validate(['file' => 'required|file|max:51200']); // 50MB demo
        $user = Auth::user();
        // checar cota
        if (($user->used_mb + ceil($request->file('file')->getSize() / (1024*1024))) > $user->quota_mb) {
            return back()->withErrors(['file' => 'Cota excedida.']);
        }

        $res = $drive->upload($request->file('file')->toArray(), $user->email);
        FileUpload::create([
            'user_id' => $user->id,
            'drive_file_id' => $res['id'],
            'drive_file_name' => $res['name'],
            'size_bytes' => $res['size'],
        ]);
        // atualiza uso
        $user->used_mb += (int)ceil($res['size'] / (1024*1024));
        $user->save();

        return back()->with('status', 'Arquivo enviado.');
    }
}
