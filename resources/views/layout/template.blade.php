<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru Hub</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 antialiased font-sans">

    <div class="min-h-screen flex flex-col md:flex-row">
        @if(auth()->user()->name === 'admin')
            @include('layout.admin-sidebar')
        @endif

        <main class="flex-1 w-full min-w-0 overflow-x-hidden">
            <div class="p-4 sm:p-6 md:p-8">
            {{-- <h1 class="text-2xl font-bold mb-4 text-center">@yield('header')</h1> --}}
                {{-- ALERT --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Success! </strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Error! </strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                {{-- END ALERT --}}
                @yield('content')
            </div>
        </main>
        
    </div>

</body>
</html>