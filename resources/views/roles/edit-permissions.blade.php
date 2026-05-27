@extends('layout.template')
@section('title', 'Permissions')
@section('header', "Permissions untuk Role: $role->name")
@section('content')
    <form method="POST" action="{{ route('roles.permissions.update', $role->id) }}">
        @csrf
        <div class="grid grid-cols-2 gap-2 mb-4">
            @foreach ($permissions as $perm)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                        {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }} class="form-checkbox text-indigo-600">
                    <span>{{ $perm->name }}</span>
                </label>
            @endforeach
        </div>
        <div class="flex justify-between">
            <a href="{{ route('roles.index') }}"
                class="inline-block bg-slate-300 text-slate-800 px-4 py-2 rounded hover:bg-slate-400">
                Kembali
            </a>
            <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded">Simpan</button>
        </div>
    </form>
@endsection
