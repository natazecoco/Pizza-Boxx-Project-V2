{{-- resources/views/partials/customer/header.blade.php --}}

<div x-data="{ mobileMenuOpen: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     class="w-full flex justify-center px-2 lg:px-6 relative"> 
    
    {{-- 
        THE PILL CONTAINER
        - Ukuran font dan logo sudah dikembalikan ke ukuran asli (Lebih besar).
        - Logic 'fixed' dihapus (karena sudah diurus Layout), tapi logic 'scrolled' tetap ada.
    --}}
    <div :class="{ 
            'bg-white/100 py-3 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] border-brand-red/20': scrolled, 
            'bg-white/90 py-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] border-gray-900': !scrolled 
         }"
         class="backdrop-blur-md border-2 rounded-[2.5rem] px-6 lg:px-12 flex items-center justify-between transition-all duration-500 ease-in-out w-full max-w-7xl">
        
        {{-- LOGO (Ukuran Kembali Besar) --}}
        <div class="flex-1 flex items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <img src="{{ asset('images/pizza-boxx-logo.png') }}" alt="Logo"
                     class="h-12 w-12 lg:h-14 lg:w-14 object-contain transition-transform duration-500 group-hover:rotate-12">
            </a>
        </div>

        {{-- DESKTOP NAV (Font Kembali ke 14px) --}}
        <div class="hidden lg:flex items-center justify-center gap-2">
            @php
                $navLinks = [
                    ['route' => 'home', 'label' => 'HOME'],
                    ['route' => 'menu.index', 'label' => 'MENU'],
                    ['route' => 'about', 'label' => 'ABOUT'],
                    ['route' => 'contact', 'label' => 'CONTACT'],
                ];
            @endphp

            @foreach($navLinks as $link)
                <a href="{{ route($link['route']) }}"
                   class="px-5 py-2 text-[14px] font-black tracking-widest transition-all duration-300 rounded-full
                          {{ request()->routeIs($link['route']) 
                             ? 'bg-brand-red text-white shadow-lg shadow-red-100' 
                             : 'text-gray-900 hover:text-brand-red hover:bg-red-50' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>

        {{-- RIGHT SECTION --}}
        <div class="flex-1 flex items-center justify-end gap-3 sm:gap-4">
            {{-- CART --}}
            <a href="{{ route('cart.index') }}" class="relative p-3 rounded-2xl bg-gray-50 text-gray-900 hover:bg-gray-900 hover:text-white transition-all group border-2 border-transparent hover:border-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
                @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 bg-brand-red text-white text-[9px] font-black rounded-full w-5 h-5 flex items-center justify-center border-2 border-white animate-bounce">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            {{-- USER (Desktop Only) --}}
            <div class="hidden lg:block">
                @if(Auth::check() && !Auth::user()->hasAnyRole(['admin', 'employee']))
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" 
                                class="flex items-center gap-2 p-1 pr-4 rounded-full bg-slate-50 border-2 border-gray-100 hover:border-gray-900 transition-all">
                            <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center text-white text-xs font-black shadow-md">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-[10px] font-black text-gray-900 uppercase tracking-widest italic truncate max-w-[80px]">
                                {{ Str::of(Auth::user()->name)->explode(' ')->first() }}
                            </span>
                        </button>

                        <div x-show="open" x-transition 
                             class="absolute right-0 mt-4 w-64 bg-white border-2 border-gray-900 rounded-[2rem] shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] py-4 z-50 overflow-hidden"
                             style="display: none;">
                            
                            <div class="px-6 py-3 border-b border-gray-100 mb-2">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Signed in as</p>
                                <p class="text-sm font-black text-gray-900 truncate uppercase italic tracking-tighter">{{ Auth::user()->name }}</p>
                            </div>
                            <div class="px-2 space-y-1">
                                <a href="{{ route('user.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-red-50 text-gray-600 hover:text-brand-red transition-all font-black text-[10px] uppercase tracking-widest">
                                    <i class="fas fa-user-circle text-lg w-6 text-center"></i> Profil Saya
                                </a>
                                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-red-50 text-gray-600 hover:text-brand-red transition-all font-black text-[10px] uppercase tracking-widest">
                                    <i class="fas fa-box-open text-lg w-6 text-center"></i> Pesanan
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="pt-2 border-t border-gray-50 mt-2">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl bg-gray-900 text-white transition-all font-black text-[10px] uppercase tracking-widest hover:bg-brand-red">
                                        <i class="fas fa-sign-out-alt text-lg w-6 text-center"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <button @click.prevent="$dispatch('open-auth-modal', { form: 'login' })"
                            class="px-6 py-2.5 rounded-full bg-brand-red text-white text-[10px] font-black uppercase tracking-widest shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] border-2 border-transparent transition-all">
                        Login
                    </button>
                @endif
            </div>

            {{-- HAMBURGER BUTTON --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 bg-gray-100 rounded-xl text-gray-900 active:scale-90 transition-all border-2 border-transparent hover:border-gray-900">
                <i class="fas" :class="mobileMenuOpen ? 'fa-times text-xl' : 'fa-bars-staggered text-xl'"></i>
            </button>
        </div>
    </div>

    {{-- 2. MOBILE MENU OVERLAY --}}
    <div x-show="mobileMenuOpen" 
         @click.away="mobileMenuOpen = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-[-10px]"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-[-10px]"
         class="lg:hidden absolute top-full left-4 right-4 mt-4 bg-white border-2 border-gray-900 rounded-[2.5rem] p-6 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] z-40 overflow-hidden" 
         style="display: none;">
        
        <div class="space-y-4">
            {{-- Nav Links --}}
            <div class="space-y-2">
                @foreach($navLinks as $link)
                    {{-- UPDATE: Tambah Hover Brand Red --}}
                    <a href="{{ route($link['route']) }}" 
                       class="block px-6 py-4 rounded-2xl font-black text-sm tracking-widest uppercase transition-all
                              {{ request()->routeIs($link['route']) 
                                 ? 'bg-brand-red text-white shadow-md' 
                                 : 'bg-gray-50 text-gray-500 border border-gray-100 hover:bg-brand-red hover:text-white hover:border-brand-red hover:shadow-md' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            <hr class="border-gray-200 border-dashed">

            @if(Auth::check())
                {{-- Info Akun Mobile --}}
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-3xl border-2 border-gray-100">
                    <div class="w-12 h-12 rounded-2xl bg-gray-900 flex items-center justify-center text-white font-black shadow-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Halo,</p>
                        <p class="text-sm font-black text-gray-900 uppercase italic leading-tight truncate max-w-[150px]">{{ Auth::user()->name }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    {{-- UPDATE: Hover Effect di Menu Profil Mobile --}}
                    <a href="{{ route('user.profile') }}" 
                       class="flex flex-col items-center justify-center p-4 bg-white rounded-[2rem] border-2 border-gray-100 text-gray-600 hover:bg-brand-red hover:text-white hover:border-brand-red active:bg-brand-red active:text-white active:border-brand-red transition-all shadow-sm">
                        <i class="fas fa-user-circle text-xl mb-2"></i>
                        <span class="text-[9px] font-black uppercase tracking-widest">Profil</span>
                    </a>
                    <a href="{{ route('user.dashboard') }}" 
                       class="flex flex-col items-center justify-center p-4 bg-white rounded-[2rem] border-2 border-gray-100 text-gray-600 hover:bg-brand-red hover:text-white hover:border-brand-red active:bg-brand-red active:text-white active:border-brand-red transition-all shadow-sm">
                        <i class="fas fa-box-open text-xl mb-2"></i>
                        <span class="text-[9px] font-black uppercase tracking-widest">Orderan</span>
                    </a>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full p-4 rounded-[2rem] bg-gray-900 text-white font-black text-[10px] uppercase tracking-[0.2em] flex items-center justify-center gap-3 hover:bg-brand-red active:bg-brand-red transition-all shadow-md">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
            @else
                <button @click="mobileMenuOpen = false; $dispatch('open-auth-modal', { form: 'login' })" 
                        class="w-full p-5 rounded-[2.5rem] bg-gray-900 text-white font-black text-xs uppercase tracking-[0.2em] shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none hover:bg-brand-red transition-all">
                    MASUK KE AKUN
                </button>
            @endif
        </div>
    </div>
</div>