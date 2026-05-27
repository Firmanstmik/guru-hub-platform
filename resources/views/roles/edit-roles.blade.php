@extends('layout.template')
@section('title', 'Edit Role')
@section('header', 'Edit Role')
@section('content')
    <form action="{{ route('roles.post.edit', $role->id) }}" method="POST" class="bg-white shadow rounded p-6 w-full max-w-md">
    @csrf

    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Role</label>
        <input type="text" name="name" id="name" required
            value="{{ old('name', $role->name) }}"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
    </div>

    <div class="flex justify-between">
        <a href="{{ route('roles.index') }}"
            class="inline-block bg-slate-300 text-slate-800 px-4 py-2 rounded hover:bg-slate-400">
            Kembali
        </a>
        <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded">Update</button>
    </div>
</form>
@endsection
