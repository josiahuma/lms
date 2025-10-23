<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- SimpleMDE Markdown Editor -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
        <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <div class="flex">
                        <div class="w-64 h-screen bg-gray-100 p-4">
                            <h2 class="text-lg font-bold mb-4">Admin Panel</h2>
                            <ul class="space-y-2">
                                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li><a href="{{ route('admin.home-settings.edit') }}">Home Settings</a></li>
                                <li><a href="{{ route('courses.index') }}">Approve Courses</a></li>
                                <li><a href="#">Manage Users</a></li>
                            </ul>
                        </div>
                        <div class="flex-1 p-6">
                            {{ $slot }}
                        </div>
                    </div>
                    @else
                        {{ $slot }}
                    @endif
            </main>
        </div>
    </body>
</html>
