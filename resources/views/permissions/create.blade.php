@extends('layout.template')
@section('title', 'Tambah Permission')
@section('header', 'Tambah Permission Baru')
@section('content')
    <form action="{{ route('permissions.store') }}" method="POST"
        class="bg-white shadow rounded p-6 w-full max-w-md space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Permission <span class="text-green-600">(ex: view-users)</span></label>
            <input type="text" name="name" id="name" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200"
                placeholder="masukkan nama permission">
        </div>

        <div>
            <label for="controller" class="block text-sm font-medium text-gray-700">Nama Controller <span class="text-green-600">(ex: UserController)</span></label>
            <input type="text" name="controller" id="controller"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200"
                placeholder="masukkan nama controller">
        </div>
        <div>
            <label for="uri" class="block text-sm font-medium text-gray-700">URL <span class="text-green-600">(ex: /users or /users/{user})</span></label>
            <input type="text" name="uri" id="uri"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200"
                placeholder="masukkan link">
        </div>

        <div>
            <label for="method" class="block text-sm font-medium text-gray-700">Method</label>
            <select name="method" id="method" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
                <option value="">Pilih Method</option>
                <option value="get">GET</option>
                <option value="post">POST</option>
                <option value="put">PUT</option>
                <option value="patch">PATCH</option>
                <option value="delete">DELETE</option>
            </select>
        </div>

        <div>
            <label for="action" class="block text-sm font-medium text-gray-700">Nama Fungsi <span class="text-green-600">(ex: index, store, dll.)</span></label>
            <input type="text" name="action" id="action"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200"
                placeholder="masukkan nama function">
        </div>

        <div class="flex justify-between pt-4">
            <a href="{{ route('permissions.index') }}"
                class="inline-block bg-slate-300 text-slate-800 px-4 py-2 rounded hover:bg-slate-400">
                Kembali
            </a>
            <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded hover:bg-slate-800">
                Simpan
            </button>
        </div>
    </form>

@endsection
