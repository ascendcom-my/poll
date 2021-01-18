<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Poll - {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('vendor/poll/css/app.css') }}">
        @stack('style')

        <!-- Scripts -->
        @stack('script')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $header }} - {{ config('app.name', 'Laravel') }}
                    </h2>
                    <div>
                        {{ $rightSide ?? '' }}
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <x-poll::status-flash></x-poll::status-flash>
                {{ $slot }}
            </main>
        </div>
        @stack('modal')
    </body>
</html>
