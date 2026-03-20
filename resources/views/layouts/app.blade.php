<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Solusi "Logout Kepotong": 
               Memaksa area navigasi/sidebar agar bisa di-scroll secara mandiri 
               jika tinggi layar laptop/HP terlalu pendek.
            */
            nav, aside, .sidebar {
                max-height: 100vh !important;
                overflow-y: auto !important;
                -ms-overflow-style: none;  /* Untuk IE dan Edge */
                scrollbar-width: none;  /* Untuk Firefox */
            }
            
            /* Menyembunyikan visual scrollbar agar desain tetap elegan dan bersih */
            nav::-webkit-scrollbar, aside::-webkit-scrollbar, .sidebar::-webkit-scrollbar {
                display: none; 
            }
            
            /* Mencegah halaman goyang ke kiri-kanan */
            body {
                overflow-x: hidden;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            
            @include('layouts.navigation')

            <main>
                {{ $slot }}
            </main>
            
        </div>
    </body>
</html>