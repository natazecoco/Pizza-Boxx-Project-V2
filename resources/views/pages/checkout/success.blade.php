<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Berhasil! | Pizza Boxx</title>

    {{-- LIBRARIES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,600;0,800;1,800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/pizza-boxx-logo.png') }}" type="image/x-icon">
    
    {{-- CONFETTI LIB --}}
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-red': '#DC2626',
                        'brand-dark': '#111827',
                        'brand-yellow': '#FCD34D',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    animation: {
                        'bounce-slow': 'bounce 3s infinite',
                    }
                }
            }
        }
    </script>

    <style>
        body { background-color: #F8FAFC; color: #111827; }
        .bg-pizza-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 15l-5.5 11h11L30 15zm0-10l15 30H15L30 5z' fill='%23DC2626' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        /* Pattern khusus untuk sisi kiri (warna putih transparan) */
        .bg-pizza-pattern-white {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 15l-5.5 11h11L30 15zm0-10l15 30H15L30 5z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="min-h-screen bg-pizza-pattern flex items-center justify-center p-4 lg:p-8">

    {{-- MAIN CONTAINER (Lebar di Desktop) --}}
    <div class="w-full max-w-5xl relative">
        
        {{-- Shadow Decoration --}}
        <div class="absolute inset-0 bg-gray-900 rounded-[2.5rem] transform translate-x-2 translate-y-2 md:translate-x-4 md:translate-y-4"></div>

        {{-- KARTU UTAMA (Flex Column di HP, Row di Desktop) --}}
        <div class="relative bg-white border-2 border-gray-900 rounded-[2.5rem] overflow-hidden flex flex-col md:flex-row shadow-xl">
            
            {{-- BAGIAN KIRI: HERO / CELEBRATION (Merah) --}}
            <div class="md:w-5/12 bg-brand-red bg-pizza-pattern-white p-8 md:p-12 flex flex-col justify-center items-center text-center relative overflow-hidden border-b-2 md:border-b-0 md:border-r-2 border-gray-900">
                
                {{-- Icon Check --}}
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mb-6 border-4 border-gray-900 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.2)] animate-bounce-slow relative z-10">
                    <i class="fas fa-check text-4xl text-brand-red"></i>
                </div>

                <div class="relative z-10 text-white">
                    <h1 class="text-3xl md:text-4xl font-black italic uppercase tracking-tighter mb-3 leading-none">Order<br>Received!</h1>
                    <p class="text-sm md:text-base font-medium opacity-90 max-w-xs mx-auto">
                        Terima kasih, <span class="font-black underline decoration-wavy decoration-white/50">{{ explode(' ', $order->customer_name)[0] }}</span>!<br>
                        Pesananmu sudah masuk dapur kami.
                    </p>
                </div>

                {{-- Hiasan Circle --}}
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute top-10 right-10 w-20 h-20 bg-yellow-400/20 rounded-full blur-xl"></div>
            </div>

            {{-- BAGIAN KANAN: DETAILS & ACTION (Putih) --}}
            <div class="md:w-7/12 p-8 md:p-12 bg-white flex flex-col justify-center">

                {{-- 1. STATUS BOX (PIN / ESTIMASI) --}}
                <div class="bg-slate-50 border-2 border-gray-900 rounded-2xl p-6 mb-8 relative group">
                    @if($order->order_type === 'pickup')
                        {{-- PICKUP MODE --}}
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">PICKUP PIN</p>
                            <i class="fas fa-store text-gray-300"></i>
                        </div>
                        <div class="text-5xl md:text-6xl font-black text-brand-red tracking-widest font-mono text-center my-2">
                            {{ $order->pickup_pin }}
                        </div>
                        <div class="border-t-2 border-dashed border-gray-200 mt-4 pt-3 text-center">
                            <p class="text-[10px] font-bold text-gray-500 flex items-center justify-center gap-1">
                                <i class="fas fa-info-circle"></i> Tunjukkan PIN ini ke kasir outlet.
                            </p>
                        </div>
                    @else
                        {{-- DELIVERY MODE --}}
                        <div class="flex justify-between items-start mb-4">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">ESTIMASI SAMPAI</p>
                            <div class="bg-green-100 text-green-700 px-2 py-1 rounded text-[10px] font-black uppercase">Confirmed</div>
                        </div>
                        
                        <div class="flex items-center gap-4 mb-4">
                            <div class="text-4xl md:text-5xl font-black text-gray-900">30-45</div>
                            <div class="text-xs font-bold text-gray-400 uppercase leading-tight">Menit<br>Lagi</div>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden border border-gray-300">
                            <div class="bg-brand-red h-full rounded-full animate-[width_2s_ease-in-out_infinite]" style="width: 45%"></div>
                        </div>
                    @endif
                </div>

                {{-- 2. GRID INFO KECIL --}}
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1">No. Order</p>
                        <p class="text-sm font-black text-gray-900 uppercase">#{{ $order->order_code ?? $order->id }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Bayar</p>
                        <p class="text-sm font-black text-brand-red">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- 3. TOMBOL AKSI --}}
                <div class="space-y-3">
                    {{-- Primary Button --}}
                    <a href="{{ route('user.dashboard') }}" class="group block w-full bg-brand-dark hover:bg-brand-red text-white text-center font-black py-4 rounded-xl uppercase tracking-widest text-xs transition-all shadow-[4px_4px_0px_0px_rgba(220,38,38,1)] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[1px] hover:translate-y-[1px] border-2 border-transparent">
                        <span class="group-hover:hidden">Lacak Pesanan</span>
                        <span class="hidden group-hover:inline">Lihat Status <i class="fas fa-arrow-right ml-1"></i></span>
                    </a>
                    
                    {{-- Secondary Buttons (Grid) --}}
                    <div class="grid grid-cols-2 gap-3">
                        <a href="https://wa.me/6281218928030?text=Halo%20PizzaBoxx%2C%20saya%20mau%20konfirmasi%20order%20%23{{ $order->id }}" target="_blank" class="flex items-center justify-center gap-2 bg-white hover:bg-green-50 text-gray-900 font-bold py-3 rounded-xl uppercase text-[10px] transition-all border-2 border-gray-200 hover:border-green-500 hover:text-green-600">
                            <i class="fab fa-whatsapp text-lg"></i> Konfirmasi
                        </a>
                        <a href="{{ route('home') }}" class="flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-900 font-bold py-3 rounded-xl uppercase text-[10px] transition-all border-2 border-gray-200 hover:border-brand-red hover:text-brand-red">
                            <i class="fas fa-home text-lg text-sm"></i> Home
                        </a>
                    </div>
                </div>

            </div>
        </div>
        
        {{-- Footer Kecil --}}
        <div class="text-center mt-8">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] opacity-50">Pizza Boxx • Good Food Good Mood</p>
        </div>

    </div>

    {{-- SCRIPT CONFETTI --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            var duration = 3 * 1000;
            var animationEnd = Date.now() + duration;
            var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

            function randomInRange(min, max) { return Math.random() * (max - min) + min; }

            var interval = setInterval(function() {
                var timeLeft = animationEnd - Date.now();
                if (timeLeft <= 0) return clearInterval(interval);
                var particleCount = 50 * (timeLeft / duration);
                confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
                confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
            }, 250);
        });
    </script>

</body>
</html>