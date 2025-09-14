@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
  @if (session('status'))
    <div class="bg-green-100 p-3 rounded">{{ session('status') }}</div>
  @endif

  <div class="bg-white shadow p-6 rounded">
    <h2 class="text-xl font-semibold mb-3">Google Drive</h2>
    <a class="underline text-blue-600" href="{{ route('google.connect') }}">Conectar ou verificar Google</a>
  </div>

  <div class="bg-white shadow p-6 rounded">
    <h3 class="font-semibold mb-2">Enviar arquivo</h3>
    <form method="post" action="{{ route('drive.upload') }}" enctype="multipart/form-data">
      @csrf
      <input type="file" name="file" required class="border p-2 w-full">
      @error('file') <div class="text-red-600 mt-2">{{ $message }}</div> @enderror
      <button class="mt-3 px-4 py-2 bg-blue-600 text-white rounded">Enviar</button>
    </form>
  </div>

  <div class="bg-white shadow p-6 rounded">
    <h3 class="font-semibold mb-2">Meus arquivos</h3>
    <table class="w-full text-sm">
      <thead><tr><th class="text-left">Nome</th><th class="text-left">Tamanho</th><th>Link</th></tr></thead>
      <tbody>
        @foreach ($files as $f)
          <tr class="border-t">
            <td>{{ $f->drive_file_name }}</td>
            <td>{{ number_format($f->size_bytes) }} bytes</td>
            <td><a class="underline" target="_blank" href="https://drive.google.com/file/d/{{ $f->drive_file_id }}/view">Abrir</a></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
