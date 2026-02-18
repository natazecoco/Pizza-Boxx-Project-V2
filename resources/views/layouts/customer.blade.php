<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pizza Boxx Order</title>
    
    {{-- SEO & Icons --}}
    <meta name="description" content="Order delicious authentic Italian pizza with fresh ingredients. Fast delivery and great taste guaranteed.">
    <link rel="icon" href="{{ asset('images/pizza-boxx-logo.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

    {{-- Fonts & Preload --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="preload" href="{{ asset('images/pizza-boxx-logo.png') }}" as="image">

    {{-- Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <style>
        [x-cloak] { display: none !important; }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .animate-marquee { animation: marquee 30s linear infinite; }
        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }
        .animate-bounce { animation: bounce 0.8s infinite; }
        @keyframes fade-in-down { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-down { animation: fade-in-down 0.5s ease-out forwards; }
    </style>

    @stack('styles') 
</head>

<body class="font-sans antialiased bg-white text-gray-800"
    x-data="{ 
        isModalOpen: {{ ($errors->has('email') && !old('message')) || $errors->has('password') || $errors->has('name_register') ? 'true' : 'false' }}, 
        isLogin: {{ $errors->has('name_register') ? 'false' : 'true' }} 
    }"
    @open-auth-modal.window="isModalOpen = true; isLogin = ($event.detail.form === 'login')"
    @close-modal.window="isModalOpen = false"
    x-bind:class="{ 'overflow-hidden': isModalOpen }">
    
    @include('partials.shared.overlays')

    {{-- 
        CRITICAL UPDATE:
        Wrapper ID 'main-navbar-pill' ini WAJIB ada di sini agar script di halaman Menu bisa
        menyembunyikan/memunculkan navbar saat discroll.
        
        Class 'fixed', 'top-0'/'top-6', dan 'transition-transform' ditaruh di sini.
        Pastikan di dalam file 'partials.customer.header' TIDAK ADA class 'fixed' lagi supaya tidak double.
    --}}
    <header id="main-navbar-pill" class="fixed top-6 left-0 right-0 z-50 transition-transform duration-500 ease-in-out">
        @include('partials.customer.header')
    </header>
    
    {{-- Main Content --}}
    <main class="flex-grow">
        @yield('content')
    </main>
    
    @include('partials.customer.footer')
    @include('partials.customer.auth-modal')

    {{-- --- NOTIFIKASI TOAST (Updated Style: Neo-Brutalist) --- --}}
    @if(session('success') || session('error') || $errors->any())
        <div x-data="{ show: true }" 
             x-init="setTimeout(() => show = false, 5000)" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-[-20px]"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-[-20px]"
             class="fixed top-28 right-6 z-[9999]"
             x-cloak>
            
            <div class="bg-gray-900 text-white px-6 py-4 rounded-2xl shadow-[8px_8px_0px_0px_rgba(0,0,0,0.2)] flex items-center gap-4 border-2 border-white lg:min-w-[300px]">
                {{-- Icon Logic --}}
                @if(session('success'))
                    <div class="w-8 h-8 rounded-full bg-emerald-400 flex items-center justify-center text-gray-900 border-2 border-white">
                        <i class="fas fa-check text-xs"></i>
                    </div>
                @else
                    <div class="w-8 h-8 rounded-full bg-brand-red flex items-center justify-center text-white border-2 border-white">
                        <i class="fas fa-exclamation text-xs"></i>
                    </div>
                @endif

                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-70 mb-0.5">Notification</p>
                    <p class="text-xs font-black uppercase italic tracking-wider">
                        {{ session('success') ?? (session('error') ?? 'Cek inputan Anda kembali.') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- --- SCRIPTS & PIZZAALERT CONFIG --- --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const PizzaAlert = Swal.mixin({
            customClass: {
                // Style Tombol & Popup disesuaikan dengan tema Brutalist (Border tebal, Shadow keras)
                confirmButton: 'bg-gray-900 text-white font-black uppercase italic px-6 py-3 rounded-xl mx-2 hover:bg-brand-red transition-all text-xs tracking-widest shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] border-2 border-transparent hover:border-gray-900 hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px]',
                cancelButton: 'bg-white text-gray-900 font-black uppercase italic px-6 py-3 rounded-xl mx-2 hover:bg-gray-100 transition-all text-xs tracking-widest shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] border-2 border-gray-900 hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px]',
                popup: 'rounded-[2rem] border-4 border-gray-900 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] p-8',
                title: 'font-black uppercase italic text-gray-900 tracking-tighter text-3xl mb-2',
                htmlContainer: 'font-bold text-gray-500 text-xs uppercase tracking-widest leading-relaxed'
            },
            buttonsStyling: false,
            showClass: { popup: 'animate-fade-in-down' },
            hideClass: { popup: 'animate-fade-out-up' } // Default Swal animation
        });
    </script>

    @stack('scripts')
</body>
</html>