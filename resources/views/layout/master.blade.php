<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Role & Permission')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white min-h-screen text-slate-900">
{{-- <body class="bg-white flex items-center justify-center min-h-screen px-4"> --}}
    <nav class="bg-white text-slate-900 px-6 py-3 shadow">
        <div class="container mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <a href="{{ route('akses.index') }}" class="hover:text-slate-700">All Akses</a>
                <a href="{{ url('/users') }}" class="hover:text-slate-700">Users</a>
                <a href="{{ route('roles.index') }}" class="hover:text-slate-700">Roles</a>
                <a href="{{ route('permissions.index') }}" class="hover:text-slate-700">Permissions</a>
                <a href="{{ route('users-manage.index') }}" class="hover:text-slate-700">Manage Roles</a>
                <a href="{{ url('/logout') }}" class="hover:text-slate-700">Logout</a>
            </div>
            <div>
                <span class="text-sm">Hi, {{ Auth::user()->name ?? 'Guest' }}</span>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">@yield('header')</h1>
        
        @if(session('success'))
            <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded-r shadow-sm text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-100 border-l-4 border-rose-500 text-rose-700 p-4 mb-6 rounded-r shadow-sm text-sm">
                {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </div>
</body>

</html>
