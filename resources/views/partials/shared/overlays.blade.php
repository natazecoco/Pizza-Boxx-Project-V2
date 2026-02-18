<!-- {{-- SUCCESS NOTIFICATION --}}
@if(session('success'))
    <div x-data="{ show: true }"
         x-init="setTimeout(() => show = false, 4000)"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-12"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-12"
         {{-- Lokasi: Pojok kanan bawah agar tidak menutupi navigasi --}}
         class="fixed bottom-10 right-10 z-[100] flex items-center gap-4 bg-gray-900 text-white p-2 pr-8 rounded-[1.5rem] shadow-2xl border-b-4 border-emerald-500 overflow-hidden">
        
        {{-- Ikon Bulat --}}
        <div class="w-12 h-12 bg-emerald-500 rounded-[1.2rem] flex items-center justify-center text-white shadow-lg">
            <i class="fas fa-check-circle text-xl"></i>
        </div>

        {{-- Teks --}}
        <div class="flex flex-col">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/40 leading-none mb-1">Berhasil</p>
            <p class="text-sm font-black uppercase italic tracking-tighter">{{ session('success') }}</p>
        </div>

        {{-- Progress Bar (Visual Timer) --}}
        <div class="absolute bottom-0 left-0 h-1 bg-emerald-400 animate-progress-shrink"></div>
    </div>
@endif

{{-- ERROR NOTIFICATION --}}
@if(session('error'))
    <div x-data="{ show: true }"
         x-init="setTimeout(() => show = false, 4000)"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-12"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         class="fixed bottom-10 right-10 z-[100] flex items-center gap-4 bg-gray-900 text-white p-2 pr-8 rounded-[1.5rem] shadow-2xl border-b-4 border-brand-red overflow-hidden">
        
        <div class="w-12 h-12 bg-brand-red rounded-[1.2rem] flex items-center justify-center text-white shadow-lg">
            <i class="fas fa-exclamation-triangle text-xl"></i>
        </div>

        <div class="flex flex-col">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/40 leading-none mb-1">Terjadi Kesalahan</p>
            <p class="text-sm font-black uppercase italic tracking-tighter">{{ session('error') }}</p>
        </div>
    </div>
@endif -->

<style>
    @keyframes progress-shrink {
        from { width: 100%; }
        to { width: 0%; }
    }
    .animate-progress-shrink {
        animation: progress-shrink 4s linear forwards;
    }
</style>