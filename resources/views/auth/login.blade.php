@extends('layouts.app')

@section('content')
@php $type = $type ?? 'customer'; @endphp

<div class="min-h-screen flex items-center justify-center bg-slate-50 p-4 lg:p-8">
    {{-- UBAHAN: Border tebal hitam + Hard Shadow --}}
    <div class="w-full max-w-5xl mx-auto grid lg:grid-cols-2 bg-white rounded-[3rem] shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] overflow-hidden border-4 border-gray-900">
        
        {{-- KIRI: Branding & Image --}}
        <div class="hidden lg:flex bg-brand-red p-12 xl:p-16 flex-col justify-between relative overflow-hidden text-white border-r-4 border-gray-900">
            <div class="absolute inset-0 bg-pizza-pattern opacity-10"></div>
            
            {{-- Floating Pizza Decoration --}}
            <img src="{{ asset('images/pizzabanner.png') }}" class="absolute -right-24 top-1/2 -translate-y-1/2 w-[120%] opacity-20 rotate-12 blur-sm pointer-events-none">

            <div class="relative z-10">
                <a href="{{ route('home') }}" class="inline-block mb-10 group">
                    <div class="bg-white p-3 rounded-2xl border-2 border-gray-900 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] group-hover:translate-x-1 group-hover:translate-y-1 group-hover:shadow-none transition-all">
                        <img src="{{ asset('images/pizza-boxx-logo.png') }}" class="w-12 h-12 object-contain">
                    </div>
                </a>
                <h2 class="text-5xl font-black italic uppercase tracking-tighter leading-[0.9] mb-6 drop-shadow-md">
                    PIZZA<br>TERENAK<br><span class="text-brand-kraft text-6xl">SEJAGAT.</span>
                </h2>
                <p class="text-white/90 font-bold text-sm leading-relaxed max-w-xs border-l-4 border-brand-kraft pl-4">
                    Masuk ke akun Anda dan nikmati promo eksklusif setiap harinya.
                </p>
            </div>

            <div class="relative z-10 bg-gray-900 p-6 rounded-3xl border-2 border-white/20 shadow-xl mt-12">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-brand-kraft flex items-center justify-center text-brand-red font-black text-xl">“</div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] mb-1 text-brand-kraft">Trending Review</p>
                        <p class="text-sm font-bold italic text-white">"Keju melimpah, harga ramah di kantong!"</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- KANAN: Form Section --}}
        <div class="p-8 md:p-16 flex flex-col justify-center bg-white relative">
            {{-- Tombol Balik Mobile --}}
            <a href="{{ route('home') }}" class="lg:hidden absolute top-6 right-6 text-gray-400 hover:text-brand-red">
                <i class="fas fa-times text-xl"></i>
            </a>

            <div class="mb-8 lg:hidden text-center">
                <img src="{{ asset('images/pizza-boxx-logo.png') }}" class="w-20 mx-auto">
            </div>

            <div class="mb-10 text-center lg:text-left">
                <h1 class="text-3xl lg:text-5xl font-black text-gray-900 italic uppercase tracking-tighter leading-none mb-2">
                    {{ $type === 'employee' ? 'PORTAL STAFF' : 'WELCOME BACK' }}
                </h1>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">
                    {{ $type === 'employee' ? 'Area Terbatas - Khusus Karyawan' : 'Silakan masuk untuk mulai memesan' }}
                </p>
            </div>

            {{-- Alert Error --}}
            @if ($errors->any())
                <div class="bg-red-50 border-2 border-brand-red p-4 mb-8 rounded-xl flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-brand-red mt-0.5"></i>
                    <div>
                        <p class="text-brand-red text-[10px] font-black uppercase tracking-widest">Login Gagal</p>
                        <p class="text-xs text-red-600 font-medium">Periksa email atau password Anda.</p>
                    </div>
                </div>
            @endif

            @if($type === 'employee')
                {{-- Form Pegawai --}}
                <form method="POST" action="{{ route('pegawai.login') }}" class="space-y-6">
                    @csrf
                    <div class="group">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2 ml-1 group-focus-within:text-brand-red transition-colors">ID Karyawan / Email</label>
                        <input name="email" type="email" required 
                               class="w-full px-5 py-4 bg-white border-2 border-gray-200 rounded-xl focus:border-gray-900 focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)] outline-none font-bold text-gray-900 transition-all placeholder-gray-300" 
                               placeholder="staff@pizzaboxx.com">
                    </div>
                    
                    <div class="group">
                        {{-- UPDATED: Tambah Link Lupa Password (Dummy) --}}
                        <div class="flex justify-between items-center mb-2 ml-1">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 group-focus-within:text-brand-red transition-colors">Sandi Akses</label>
                            <a href="javascript:void(0)" 
                               onclick="Swal.fire({icon: 'info', title: 'Lupa Password?', text: 'Silakan hubungi IT Support untuk reset akses pegawai.', confirmButtonColor: '#DC2626'})" 
                               class="text-[9px] font-black uppercase tracking-widest text-brand-red hover:underline italic transition-colors">
                               Lupa?
                            </a>
                        </div>
                        
                        <input name="password" type="password" required 
                               class="w-full px-5 py-4 bg-white border-2 border-gray-200 rounded-xl focus:border-gray-900 focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)] outline-none font-bold text-gray-900 transition-all placeholder-gray-300" 
                               placeholder="••••••••">
                    </div>
                    
                    <button type="submit" class="w-full bg-gray-900 text-white font-black py-5 rounded-xl uppercase tracking-[0.2em] text-xs shadow-[4px_4px_0px_0px_rgba(220,38,38,1)] hover:bg-brand-red hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[2px] hover:translate-y-[2px] transition-all border-2 border-transparent">
                        MASUK DASHBOARD
                    </button>
                </form>
            @else
                {{-- Form Customer (Sudah pakai partial yang kita update sebelumnya) --}}
                @include('partials.customer._login-form')
                
                <div class="mt-10 pt-8 border-t-2 border-dashed border-gray-100 text-center lg:text-left">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-brand-red hover:text-gray-900 underline decoration-2 underline-offset-2 transition-colors ml-1">Daftar Sekarang →</a>
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection