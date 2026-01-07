<aside class="hidden md:flex flex-col w-64 bg-white border-r border-gray-200 pt-6 px-4 fixed left-0 top-0 h-full z-30 shadow-lg">
    <div class="flex flex-col gap-8 h-full">

        <div class="flex items-center justify-center p-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <img src="{{ asset('images/pizza-boxx-logo.png') }}" alt="Logo" class="h-12 w-12 rounded-full shadow">
                <div class="text-xl font-bold text-red-600">Pizza Boxx</div>
            </a>
        </div>

        <nav class="flex-1 flex flex-col gap-1">
            
            <a href="{{ route('pegawai.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300
                      {{ request()->routeIs('pegawai.dashboard') ? 'bg-red-100 text-red-600 shadow-inner' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
                <i class="fa-solid fa-gauge-high w-5 h-5"></i>
                Dashboard
            </a>
            
            {{-- Grup: Manajemen Pesanan --}}
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-4">Manajemen Pesanan</div>
            <a href="{{ route('pegawai.orders.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300
                      {{ request()->routeIs('pegawai.orders.index') ? 'bg-red-100 text-red-600 shadow-inner' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
                <i class="fa-solid fa-list-ul w-5 h-5"></i>
                Daftar Pesanan
            </a>

            {{-- Grup: Manajemen Pengantaran --}}
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-4">Manajemen Pengantaran</div>
            <a href="{{ route('pegawai.deliveries.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300
                      {{ request()->routeIs('pegawai.deliveries.index') ? 'bg-red-100 text-red-600 shadow-inner' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
                <i class="fa-solid fa-truck-fast w-5 h-5"></i>
                Daftar Pengantaran
            </a>
            
            {{-- Grup Khusus Admin --}}
            @if(Auth::guard('employee')->check() && Auth::guard('employee')->user()->hasRole('admin'))
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-4">Manajemen Pusat</div>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300
                          {{ request()->routeIs('admin.dashboard') ? 'bg-red-100 text-red-600 shadow-inner' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
                    <i class="fa-solid fa-user-gear w-5 h-5"></i>
                    Dashboard Admin
                </a>
            @endif

        </nav>
        
        <div class="mt-auto p-4 border-t border-gray-200">
            <form action="{{ route('pegawai.logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold text-left w-full transition-all duration-300
                               text-gray-700 hover:bg-red-50 hover:text-red-600">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 h-5"></i>
                    Keluar
                </button>
            </form>
        </div>

    </div>
</aside>