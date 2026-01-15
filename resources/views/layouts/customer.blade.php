<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pizza Boxx Order</title>
    <meta name="description" content="Order delicious authentic Italian pizza with fresh ingredients. Fast delivery and great taste guaranteed.">

    <link rel="icon" href="{{ asset('images/pizza-boxx-logo.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <link rel="preload" href="{{ asset('images/pizza-boxx-logo.png') }}" as="image">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <style>
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            animation: marquee 30s linear infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .animate-bounce {
            animation: bounce 0.8s infinite;
        }
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.5s ease-out forwards;
        }
    </style>
</head>
<body class="font-sans antialiased bg-white text-gray-800"
    x-data="{ isModalOpen: false }"
    @open-auth-modal.window="isModalOpen = true"
    @close-modal.window="isModalOpen = false"
    x-bind:class="{ 'overflow-hidden': isModalOpen }">
    
    @include('partials.shared.overlays')
    @include('partials.customer.header')
    
    <main class="flex-grow pt-20">
        @yield('content')
    </main>
    
    @include('partials.customer.footer')
    
    @include('partials.customer.auth-modal')
    
    @stack('scripts')
</body>
</html>