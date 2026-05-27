@extends('layout.template')
@section('title', 'User Roles')
@section('header', "Role untuk User: $user->name")
@section('content')
    <form method="POST" action="{{ route('users-manage.roles.update', $user->id) }}">
        @csrf
        <div class="grid grid-cols-2 gap-2 mb-4">
            @foreach ($roles as $role)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                        {{ $user->hasRole($role->name) ? 'checked' : '' }} class="form-checkbox text-indigo-600">
                    <span>{{ $role->name }}</span>
                </label>
            @endforeach
        </div>
        <div class="flex justify-between">
            <a href="{{ route('users-manage.index') }}"
                class="inline-block bg-slate-300 text-slate-800 px-4 py-2 rounded hover:bg-slate-400">
                Kembali
            </a>
            <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded">
                Simpan
            </button>
        </div>
    </form>
@endsection
