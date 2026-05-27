@extends('layout.template')
@section('title', 'Manage User')
@section('header', 'Manage Role Users')
@section('content')
<table class="min-w-full bg-white shadow rounded">
    <thead class="bg-slate-100">
        <tr>
            <th class="text-left py-2 px-4 border border-slate-200">Nama</th>
            <th class="text-left py-2 px-4 border border-slate-200">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr class="border-t">
            <td class="py-2 px-4 border border-slate-200">{{ $user->name }}</td>
            <td class="py-2 px-4 border border-slate-200">
                <a href="{{ route('users-manage.roles', $user->id) }}" class="text-slate-900 hover:underline">Kelola Role</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
