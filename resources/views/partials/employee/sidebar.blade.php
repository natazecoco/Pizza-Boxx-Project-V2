<aside 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 w-64 bg-red-600 text-white z-50 shadow-2xl transform transition-transform duration-300 ease-in-out md:translate-x-0 h-full flex flex-col border-r border-red-700">
    
    <div class="flex flex-col h-full">
        <div class="p-6 flex items-center justify-between border-b border-white/10">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="bg-white p-1.5 rounded-full shadow-inner">
                    <img src="{{ asset('images/pizza-boxx-logo.png') }}" alt="Logo" class="h-8 w-8">
                </div>
                <div class="text-xl font-black tracking-tighter text-white uppercase">Pizza Boxx</div>
            </a>
            
            <button @click="sidebarOpen = false" class="md:hidden text-white/80 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
            
            <a href="{{ route('pegawai.dashboard') }}"
               class="flex items-center gap-3 px-4 py-4 rounded-2xl font-bold transition-all duration-300
                      {{ request()->routeIs('pegawai.dashboard') ? 'bg-white text-red-600 shadow-xl' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-gauge-high w-5"></i>
                <span>Dashboard</span>
            </a>
            
            <div class="pt-6 pb-2 px-4">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/50">Layanan Toko</p>
            </div>

            <a href="{{ route('pegawai.orders.index') }}"
               class="flex items-center gap-3 px-4 py-4 rounded-2xl font-bold transition-all duration-300
                      {{ request()->routeIs('pegawai.orders.*') ? 'bg-white text-red-600 shadow-xl' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-pizza-slice w-5"></i>
                <span>Daftar Pesanan</span>
            </a>

            <div class="pt-6 pb-2 px-4">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/50">Logistik</p>
            </div>

            <a href="{{ route('pegawai.deliveries.index') }}"
               class="flex items-center gap-3 px-4 py-4 rounded-2xl font-bold transition-all duration-300
                      {{ request()->routeIs('pegawai.deliveries.*') ? 'bg-white text-red-600 shadow-xl' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-truck-fast w-5"></i>
                <span>Pengantaran</span>
            </a>
            
            @if(Auth::guard('employee')->check() && Auth::guard('employee')->user()->hasRole('admin'))
                <div class="pt-6 pb-2 px-4 border-t border-white/10 mt-6">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/50">Otoritas Admin</p>
                </div>
                <a href="{{ route('filament.admin.pages.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-4 rounded-2xl font-bold transition-all duration-300
                          {{ request()->routeIs('filament.admin.pages.dashboard') ? 'bg-red-800 text-white shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-user-shield w-5"></i>
                    <span>Admin Central</span>
                </a>
            @endif
        </nav>

        <div class="p-4 border-t border-white/10">
            <form action="{{ route('pegawai.logout') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="flex items-center gap-3 px-4 py-4 rounded-2xl font-bold text-left w-full transition-all duration-300 text-white/80 hover:bg-black/20 hover:text-white">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5"></i>
                    <span>Keluar Aplikasi</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<style>
    /* Styling scrollbar tipis agar tidak merusak desain merah */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
</style>