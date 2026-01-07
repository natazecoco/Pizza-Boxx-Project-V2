@if(session('success'))
    <div x-data="{ show: true }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         x-init="setTimeout(() => show = false, 2500)"
         class="fixed top-6 right-6 z-50 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl shadow-xl flex items-center space-x-3 border-l-4 border-white/20">
        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span class="font-semibold">{{ session('success') }}</span>
    </div>
@endif

<div class="group fixed top-16 left-0 w-full z-30 bg-gradient-to-r from-red-600 to-orange-500 text-white py-2 overflow-hidden shadow-lg">
    <div class="animate-marquee inline-block whitespace-nowrap will-change-transform">
        @for ($i = 0; $i < 10; $i++)
            <span class="mx-8 text-sm font-bold tracking-wider" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
                🍕 DISKON 10% dengan kode <span class="uppercase bg-white/20 px-2 py-1 rounded">DISKON10</span> 🍕
            </span>
        @endfor
    </div>
</div>