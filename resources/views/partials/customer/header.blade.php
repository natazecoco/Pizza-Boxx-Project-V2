<header class="bg-white text-gray-800 shadow-md fixed top-0 left-0 w-full z-40 backdrop-blur-sm bg-white/90">
    <nav class="container mx-auto px-4 py-3 grid grid-cols-3 items-center">
        <!-- Logo -->
        <div class="flex items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group transition-all duration-200">
                <img src="{{ asset('images/pizza-boxx-logo.png') }}"
                     alt="Pizza Boxx Logo"
                     class="h-10 w-10 object-contain drop-shadow-md transition-transform duration-300 group-hover:rotate-[15deg]"
                     loading="eager">
                <span class="hidden sm:block text-xl font-bold text-red-600 group-hover:text-red-700 transition-colors uppercase">Pizza Boxx</span>
            </a>
        </div>

        <!-- Main Navigation Links -->
        <div class="flex items-center justify-center gap-4 md:gap-6">
            <a href="{{ route('home') }}"
               class="group flex items-center h-10 px-3 rounded-lg font-bold relative transition-all duration-300 whitespace-nowrap
                      {{ request()->routeIs('home') ? 'text-red-600' : 'text-gray-700 hover:text-red-600' }}">
                HOME
                <span class="absolute left-1/2 -bottom-1 -translate-x-1/2 h-0.5 bg-red-500 rounded-full transition-all duration-300
                             {{ request()->routeIs('home') ? 'w-8' : 'w-0 group-hover:w-8' }}"></span>
            </a>
            <a href="{{ route('menu.index') }}"
               class="group flex items-center h-10 px-3 rounded-lg font-bold relative transition-all duration-300 whitespace-nowrap
                      {{ request()->routeIs('menu.index') ? 'text-red-600' : 'text-gray-700 hover:text-red-600' }}">
                MENU
                <span class="absolute left-1/2 -bottom-1 -translate-x-1/2 h-0.5 bg-red-500 rounded-full transition-all duration-300
                             {{ request()->routeIs('menu.index') ? 'w-8' : 'w-0 group-hover:w-8' }}"></span>
            </a>
            <a href="{{ route('about') }}"
               class="group flex items-center h-10 px-3 rounded-lg font-bold relative transition-all duration-300 whitespace-nowrap
                      {{ request()->routeIs('about') ? 'text-red-600' : 'text-gray-700 hover:text-red-600' }}">
                ABOUT
                <span class="absolute left-1/2 -bottom-1 -translate-x-1/2 h-0.5 bg-red-500 rounded-full transition-all duration-300
                             {{ request()->routeIs('about') ? 'w-8' : 'w-0 group-hover:w-8' }}"></span>
            </a>
            <a href="{{ route('contact') }}"
               class="group flex items-center h-10 px-3 rounded-lg font-bold relative transition-all duration-300 whitespace-nowrap
                      {{ request()->routeIs('contact') ? 'text-red-600' : 'text-gray-700 hover:text-red-600' }}">
                CONTACT
                <span class="absolute left-1/2 -bottom-1 -translate-x-1/2 h-0.5 bg-red-500 rounded-full transition-all duration-300
                             {{ request()->routeIs('contact') ? 'w-8' : 'w-0 group-hover:w-8' }}"></span>
            </a>
        </div>

        <!-- User Actions (Bagian yang sudah diperbarui) -->
        <div class="flex items-center justify-end gap-3 sm:gap-5">
            <a href="{{ route('cart.index') }}"
               class="relative flex items-center justify-center w-10 h-10 transition-all duration-300 group focus:outline-none"
               aria-label="Keranjang">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6 text-gray-700 group-hover:text-red-600 transition-colors duration-200"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
                @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center border-2 border-white shadow-md transition-all duration-200 group-hover:animate-bounce">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            <!-- Dropdown Pengguna dengan Jaring Pengaman -->
            <div class="relative" x-data="{ open: false }">
                @if(Auth::guard('web')->check() && !Auth::guard('web')->user()->hasAnyRole(['admin', 'employee']))
                    <button @click="open = !open"
                            @blur="setTimeout(() => open = false, 150)"
                            class="flex items-center h-10 px-3 rounded-lg hover:bg-red-50 hover:text-red-600 transition-all duration-200 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6 text-gray-700 mr-2"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="font-bold text-base text-gray-700 hidden sm:inline-block">
                            {{ ucfirst(Str::of(Auth::user()->name)->explode(' ')->first()) }}
                        </span>
                    </button>
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl py-2 z-50 border border-gray-100 divide-y divide-gray-100"
                         style="display: none;">
                        <div class="px-4 py-3">
                            <div class="font-bold text-gray-800 truncate">{{ Auth::user()->name }}</div>
                            <div class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="py-1">
                            <a href="{{ route('user.profile') }}"
                               class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Akun Saya
                            </a>
                            <a href="{{ route('user.dashboard') }}"
                               class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Orderan Saya
                            </a>
                        </div>
                        <div class="py-1">
                            <form action="{{ route('logout') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="#" @click.prevent="$dispatch('open-auth-modal', { form: 'login' })"
                       class="flex items-center h-10 px-4 sm:px-6 rounded-xl bg-gradient-to-r from-red-600 to-red-500 text-white font-semibold shadow-md hover:from-red-700 hover:to-red-600 transition-all duration-200 hover:shadow-lg">
                        Masuk
                    </a>
                @endif
            </div>
        </div>
    </nav>
</header>