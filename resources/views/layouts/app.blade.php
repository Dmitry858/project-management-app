<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        @stack('additional-styles')
    </head>

    <body class="bg-gray-800 font-sans leading-normal tracking-normal mt-12">
        @include('include.header')

        <main>
            <div class="flex flex-col md:flex-row">
                @include('include.navigation')
                <div id="main" class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5 overflow-x-auto">
                    @include('include.title')
                    <div class="p-6">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        @stack('additional-scripts')
    </body>
</html>
