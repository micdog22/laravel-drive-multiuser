@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
  <h1 class="text-2xl font-bold mb-4">Admin - Usuários</h1>
  <table class="w-full text-sm">
    <thead>
      <tr>
        <th class="text-left">ID</th>
        <th class="text-left">Nome</th>
        <th class="text-left">E-mail</th>
        <th>Role</th>
        <th>Status</th>
        <th>Cota</th>
        <th>Uso</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $u)
      <tr class="border-t">
        <td>{{ $u->id }}</td>
        <td>{{ $u->name }}</td>
        <td>{{ $u->email }}</td>
        <td>{{ $u->role }}</td>
        <td>{{ $u->is_active ? 'Ativo':'Inativo' }}</td>
        <td>{{ $u->quota_mb }} MB</td>
        <td>{{ $u->used_mb }} MB</td>
        <td class="space-x-2">
          <form class="inline" method="post" action="{{ route('admin.toggle', $u) }}">
            @csrf <button class="px-2 py-1 bg-gray-200 rounded">{{ $u->is_active ? 'Inativar':'Ativar' }}</button>
          </form>
          <form class="inline" method="post" action="{{ route('admin.role', $u) }}">
            @csrf
            <select name="role" class="border p-1">
              <option value="user" @selected($u->role==='user')>user</option>
              <option value="admin" @selected($u->role==='admin')>admin</option>
            </select>
            <button class="px-2 py-1 bg-gray-200 rounded">Trocar</button>
          </form>
          <form class="inline" method="post" action="{{ route('admin.quota', $u) }}">
            @csrf
            <input type="number" min="1" name="quota_mb" value="{{ $u->quota_mb }}" class="border p-1 w-24">
            <button class="px-2 py-1 bg-gray-200 rounded">Salvar cota</button>
          </form>
          <a class="underline" href="{{ route('impersonate.start', $u) }}">Ver área</a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  @if (session('impersonate_admin'))
    <div class="mt-4">
      <a class="underline text-blue-600" href="{{ route('impersonate.stop') }}">Sair do modo de visualização</a>
    </div>
  @endif
</div>
@endsection
