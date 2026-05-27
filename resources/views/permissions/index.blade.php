@extends('layout.template')
@section('title', 'Permissions')
@section('header', 'Daftar Permission')
@section('content')
    <a href="{{ route('permissions.create') }}" class="mb-4 inline-block bg-slate-900 text-white px-4 py-2 rounded">+ Tambah
        Permission</a>
    <table class="min-w-full bg-white shadow rounded">
        <thead class="bg-slate-100">
            <tr>
                <th class="text-left py-2 px-4 border border-slate-200">Nama Permission</th>
                <th class="text-left py-2 px-4 border border-slate-200">Nama Controller</th>
                <th class="text-left py-2 px-4 border border-slate-200">URL</th>
                <th class="text-left py-2 px-4 border border-slate-200">Method</th>
                <th class="text-left py-2 px-4 border border-slate-200">Function</th>
                <th class="text-left py-2 px-4 border border-slate-200">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $permission)
                <tr class="border-t">
                    <td class="py-2 px-4 border border-slate-200">{{ $permission->name }}</td>
                    <td class="py-2 px-4 border border-slate-200">{{ $permission->controller }}</td>
                    <td class="py-2 px-4 border border-slate-200">{{ $permission->uri }}</td>
                    <td class="py-2 px-4 border border-slate-200">{{ $permission->method }}</td>
                    <td class="py-2 px-4 border border-slate-200">{{ $permission->action }}</td>
                    <td class="py-2 px-4 border border-slate-200">
                        <a href="{{ route('permissions.edit', $permission->id) }}"
                            class="mb-4 inline-blocktext-slate-900 px-4 py-2 rounded">Update</a>
                        <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-900 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
