@extends('layouts.customer')

@section('content')
    <div class="bg-slate-50 min-h-screen pt-32 pb-12 lg:pt-36 lg:pb-20">
        <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
            
            {{-- 1. BREADCRUMBS --}}
            <nav class="flex mb-6 items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-400">
                <a href="{{ route('home') }}" class="hover:text-brand-red transition-colors">Home</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                {{-- UPDATE: Tambahkan Hash ID Kategori di Link Menu --}}
                <a href="{{ route('menu.index') }}#category-{{ $product->category_id }}" class="hover:text-brand-red transition-colors">Menu</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-brand-red line-clamp-1 italic font-black uppercase">{{ $product->name }}</span>
            </nav>

            {{-- MAIN PRODUCT CARD --}}
            {{-- 
               PERUBAHAN PENTING 1: 
               HAPUS 'overflow-hidden'. Ini kuncinya biar Sticky jalan.
               Kita pindahkan rounded-nya ke anak-anaknya (Left & Right Col).
            --}}
            <div class="bg-white rounded-[2.5rem] lg:rounded-[3rem] shadow-[12px_12px_0px_0px_rgba(0,0,0,0.1)] border-2 border-gray-900 flex flex-col lg:flex-row relative transition-all duration-500 hover:shadow-[16px_16px_0px_0px_rgba(220,38,38,0.2)] hover:-translate-y-1">
                
                {{-- 2. BAGIAN KIRI: VISUAL PRODUK --}}
                {{-- Tambahkan class rounded manual biar bentuknya tetap melengkung --}}
                <div class="lg:w-[45%] bg-brand-red p-8 lg:p-12 flex items-center justify-center relative overflow-hidden border-b-2 lg:border-b-0 lg:border-r-2 border-gray-900 rounded-t-[2.5rem] lg:rounded-l-[3rem] lg:rounded-tr-none">
                    <div class="absolute inset-0 bg-pizza-pattern opacity-10"></div>
                    
                    <div class="relative z-10 w-full max-w-sm lg:max-w-none mx-auto group">
                        <div class="relative aspect-square">
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" 
                                     alt="{{ $product->name }}"
                                     class="relative z-10 w-full h-full object-cover rounded-[2rem] lg:rounded-[2.5rem] shadow-2xl border-2 border-gray-900 transition-transform duration-700 group-hover:scale-[1.02] group-hover:rotate-1">
                            @else
                                <div class="relative z-10 w-full h-full bg-white/10 rounded-[2rem] flex items-center justify-center border-4 border-white/5">
                                    <i class="fas fa-pizza-slice text-white/20 text-8xl"></i>
                                </div>
                            @endif
                            <div class="absolute -bottom-3 -right-3 lg:-bottom-5 lg:-right-5 w-full h-full bg-gray-900/10 rounded-[2rem] lg:rounded-[2.5rem] -z-0"></div>
                        </div>

                        <div class="absolute -bottom-4 -left-2 lg:-bottom-6 lg:-left-6 bg-brand-kraft px-5 py-4 lg:p-6 rounded-3xl shadow-xl border-2 border-gray-900 rotate-6 group-hover:rotate-0 transition-transform duration-500 z-20">
                            <p class="text-brand-red font-black text-sm lg:text-xl leading-none uppercase italic text-center">Freshly<br>Baked</p>
                        </div>
                    </div>
                </div>

                {{-- 3. BAGIAN KANAN: KONFIGURASI --}}
                {{-- Tambahkan rounded-b (Mobile) dan rounded-r (Desktop) --}}
                <div class="lg:w-[55%] flex flex-col rounded-b-[2.5rem] lg:rounded-r-[3rem] lg:rounded-bl-none bg-white">
                    
                    {{-- WRAPPER PADDING (Konten di atas tombol) --}}
                    <div class="p-6 lg:p-12 pb-0 lg:pb-0"> {{-- pb-0 biar nyambung sama sticky action bar --}}
                        <div class="mb-8 border-b-2 border-gray-100 pb-6">
                            <span class="text-brand-red font-black text-xs uppercase tracking-widest bg-red-50 px-3 py-1 rounded-full border border-red-100">{{ $product->category->name }}</span>
                            <h1 class="text-3xl lg:text-5xl font-black text-gray-900 italic uppercase tracking-tighter leading-[0.95] mt-4 mb-4">
                                {{ $product->name }}
                            </h1>
                            <p class="text-gray-500 text-sm lg:text-base font-medium leading-relaxed">
                                {{ $product->description ?? 'Nikmati perpaduan rasa autentik dengan bahan-bahan pilihan terbaik dari dapur kami.' }}
                            </p>
                        </div>

                        <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST" data-product-base-price="{{ $product->base_price }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            {{-- STEP 01: UKURAN --}}
                            @if($product->options->where('type', 'Ukuran')->isNotEmpty())
                            <div class="mb-8">
                                <label class="flex items-center gap-3 text-sm font-black uppercase tracking-widest text-gray-900 mb-4">
                                    <span class="bg-gray-900 text-white w-6 h-6 flex items-center justify-center rounded-lg text-[10px]">01</span>
                                    Pilih Ukuran
                                </label>
                                <div class="grid grid-cols-3 gap-3 lg:gap-4">
                                    @foreach($product->options->where('type', 'Ukuran') as $option)
                                        @php
                                            $suffix = match(true) {
                                                str_contains(strtolower($option->name), 'personal') => 'P',
                                                str_contains(strtolower($option->name), 'reguler') => 'R',
                                                str_contains(strtolower($option->name), 'large') => 'L',
                                                default => ''
                                            };
                                        @endphp
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="size_option_id" value="{{ $option->id }}" 
                                                   data-price-modifier="{{ $option->price_modifier }}" 
                                                   data-size-suffix="{{ $suffix }}"
                                                   class="peer hidden" required @if($loop->first) checked @endif>
                                            <div class="bg-slate-50 border-2 border-transparent py-3 px-2 lg:py-4 lg:px-4 rounded-2xl text-center transition-all peer-checked:border-gray-900 peer-checked:bg-white peer-checked:shadow-lg hover:bg-white hover:border-gray-300 h-full flex flex-col justify-center items-center">
                                                <p class="text-xs lg:text-sm font-black tracking-widest text-gray-400 peer-checked:text-brand-red uppercase">{{ $option->name }}</p>
                                                <p class="text-xs font-bold text-gray-900 mt-1 italic">+{{ number_format($option->price_modifier, 0, ',', '.') }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- STEP 02: PINGGIRAN --}}
                            @if($product->category->name === 'Pizza')
                            <div class="mb-8">
                                <label class="flex items-center gap-3 text-sm font-black uppercase tracking-widest text-gray-900 mb-4">
                                    <span class="bg-gray-900 text-white w-6 h-6 flex items-center justify-center rounded-lg text-[10px]">02</span>
                                    Pilih Pinggiran
                                </label>
                                <div id="crust-container" class="grid grid-cols-2 gap-3 lg:gap-4"></div>
                                <input type="hidden" id="crust_option_id" name="crust_option_id" required>
                            </div>
                            @endif

                            {{-- STEP 03: ADD-ONS --}}
                            @if($product->addons->isNotEmpty())
                            <div class="mb-10">
                                <label class="flex items-center gap-3 text-sm font-black uppercase tracking-widest text-gray-900 mb-4">
                                    <span class="bg-gray-900 text-white w-6 h-6 flex items-center justify-center rounded-lg text-[10px]">03</span>
                                    Tambahan Ekstra
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($product->addons as $addon)
                                    <label class="flex items-center justify-between p-3 lg:p-4 bg-slate-50 rounded-2xl cursor-pointer hover:bg-white hover:shadow-md transition-all group border-2 border-transparent hover:border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" name="addons[]" value="{{ $addon->id }}" data-price="{{ $addon->price }}"
                                                   class="w-5 h-5 rounded-lg border-gray-300 text-brand-red focus:ring-brand-red">
                                            <span class="text-xs lg:text-sm font-bold tracking-wide text-gray-600 group-hover:text-brand-red">{{ $addon->name }}</span>
                                        </div>
                                        <span class="text-xs font-black text-brand-red italic">+{{ number_format($addon->price, 0, ',', '.') }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        
                        </div> {{-- End Wrapper Padding --}}

                        {{-- 
                            4. ACTION BAR (STICKY INSIDE CARD) 
                            Ini yang kamu mau. 'sticky bottom-0' sekarang bekerja karena parentnya 
                            TIDAK punya overflow-hidden.
                        --}}
                        <div class="sticky bottom-0 z-40 bg-white/95 backdrop-blur-md border-t-2 border-dashed border-gray-200 px-6 py-4 lg:px-12 lg:py-8 mt-auto rounded-b-[2.5rem] lg:rounded-br-[3rem]">
                            
                            <div class="flex flex-col sm:flex-row items-center gap-4 lg:gap-6 justify-between">
                                <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-center">
                                    <div class="flex items-center bg-slate-100 p-1.5 rounded-2xl border border-gray-200">
                                        <button type="button" id="decreaseQuantity" class="w-10 h-10 flex items-center justify-center bg-white rounded-xl text-gray-900 hover:text-brand-red shadow-sm border border-gray-100 transition-all font-black text-xl hover:scale-105 active:scale-95">-</button>
                                        <span id="quantityDisplay" class="text-base font-black text-gray-900 px-6 min-w-[3rem] text-center">1</span>
                                        <input type="hidden" name="quantity" id="quantityInput" value="1">
                                        <button type="button" id="increaseQuantity" class="w-10 h-10 flex items-center justify-center bg-brand-red rounded-xl text-white shadow-md transition-all font-black text-xl hover:scale-105 active:scale-95 border border-brand-red">+</button>
                                    </div>

                                    <div class="text-right lg:hidden">
                                        <p class="text-[8px] font-black uppercase tracking-widest text-gray-400 mb-0.5">Total</p>
                                        <p id="totalPriceMobile" class="text-xl font-black text-brand-red italic tracking-tighter">
                                            Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="hidden lg:block text-center sm:text-right w-full sm:w-auto">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Total Biaya</p>
                                    <p id="totalPrice" class="text-3xl lg:text-4xl font-black text-brand-red italic tracking-tighter">
                                        Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <button type="submit" id="submitBtn"
                                    class="group w-full bg-gray-900 hover:bg-brand-red text-white font-black py-4 lg:py-6 px-10 rounded-2xl lg:rounded-[2rem] tracking-widest text-sm lg:text-base uppercase shadow-[4px_4px_0px_0px_rgba(220,38,38,1)] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[1px] hover:translate-y-[1px] lg:hover:translate-x-[2px] lg:hover:translate-y-[2px] transition-all duration-200 flex items-center justify-center gap-3 border-2 border-transparent mt-4 lg:mt-6">
                                <span id="btnText">Tambahkan ke Keranjang</span>
                                <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script & Style Sama Persis --}}
    {{-- ... (Paste script & style yang sama dari sebelumnya di sini) ... --}}
    {{-- LOADING OVERLAY (Tetap Sama) --}}
    <div id="loading-overlay" class="fixed inset-0 z-[200] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white p-10 rounded-[3rem] shadow-2xl flex flex-col items-center border border-gray-100 animate-fade-in text-center">
            <div class="relative w-20 h-20 mb-6">
                <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-brand-red rounded-full border-t-transparent animate-spin"></div>
                <i class="fas fa-pizza-slice text-brand-red absolute inset-0 flex items-center justify-center text-2xl"></i>
            </div>
            <h3 class="text-gray-900 font-black uppercase italic tracking-tighter text-2xl">Pesanan Diproses</h3>
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-2">Sesaat lagi masuk keranjang...</p>
        </div>
    </div>

    {{-- SCRIPT: Perlu sedikit penyesuaian di bagian innerHTML JS agar class fontnya sesuai --}}
    <script>
        const allUniversalCrusts = @json($universalCrusts);
        const basePrice = {{ $product->base_price }};
        const crustContainer = document.getElementById('crust-container');
        const crustInput = document.getElementById('crust_option_id');
        const totalPriceDisplay = document.getElementById('totalPrice');
        const quantityInput = document.getElementById('quantityInput');
        const quantityDisplay = document.getElementById('quantityDisplay');

        function updateCrustOptions() {
            if(!crustContainer) return;
            
            const selectedSize = document.querySelector('input[name="size_option_id"]:checked');
            const suffix = selectedSize ? selectedSize.getAttribute('data-size-suffix') : '';
            
            crustContainer.innerHTML = '';
            
            let relevantCrusts = allUniversalCrusts.filter(crust => {
                const name = crust.name.toLowerCase();
                if(name.includes('original')) return true; 
                const match = name.match(/\((\w)\)$/);
                return (match ? match[1].toUpperCase() : '') === suffix;
            });

            relevantCrusts.sort((a, b) => {
                if (a.name.toLowerCase().includes('original')) return -1;
                if (b.name.toLowerCase().includes('original')) return 1;
                return 0;
            });

            relevantCrusts.forEach((crust, index) => {
                const crustName = crust.name.replace(/\s*\([PRL]\)$/i, '');
                const div = document.createElement('div');
                
                // UPDATE HTML: Font size disesuaikan agar tidak kekecilan
                div.innerHTML = `
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="crust_radio" value="${crust.id}" 
                            data-price-modifier="${crust.price_modifier}"
                            class="peer hidden" ${index === 0 ? 'checked' : ''}>
                        <div class="bg-slate-50 border-2 border-transparent py-3 px-2 lg:py-4 lg:px-4 rounded-2xl text-center transition-all peer-checked:border-brand-red peer-checked:bg-white peer-checked:shadow-lg hover:bg-white hover:border-gray-200 h-full flex flex-col justify-center items-center">
                            <p class="text-xs lg:text-sm font-black tracking-widest text-gray-400 peer-checked:text-brand-red uppercase">${crustName}</p>
                            <p class="text-xs font-bold text-gray-900 mt-1 italic">+${new Intl.NumberFormat('id-ID').format(crust.price_modifier)}</p>
                        </div>
                    </label>
                `;
                crustContainer.appendChild(div.firstElementChild);
            });

            syncCrustInput();
            calculateTotal();
        }

        // ... Sisanya sama persis dengan script kamu sebelumnya ...
        function syncCrustInput() {
            const checked = document.querySelector('input[name="crust_radio"]:checked');
            if(checked) crustInput.value = checked.value;
        }

        function calculateTotal() {
            let total = basePrice;
            const size = document.querySelector('input[name="size_option_id"]:checked');
            if(size) total += parseFloat(size.getAttribute('data-price-modifier'));
            const crust = document.querySelector('input[name="crust_radio"]:checked');
            if(crust) total += parseFloat(crust.getAttribute('data-price-modifier'));
            document.querySelectorAll('input[name="addons[]"]:checked').forEach(a => total += parseFloat(a.getAttribute('data-price')));
            
            total *= parseInt(quantityInput.value);
            
            // Format Rupiah
            const formattedPrice = `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;
            
            // Update Tampilan Desktop
            if(totalPriceDisplay) totalPriceDisplay.textContent = formattedPrice;
            
            // Update Tampilan Mobile (Elemen Baru)
            const mobilePrice = document.getElementById('totalPriceMobile');
            if(mobilePrice) mobilePrice.textContent = formattedPrice;
        }

        document.querySelectorAll('input[name="size_option_id"]').forEach(el => el.addEventListener('change', updateCrustOptions));
        document.addEventListener('change', (e) => {
            if(e.target.name === 'crust_radio') { syncCrustInput(); calculateTotal(); }
            if(e.target.name === 'addons[]') calculateTotal();
        });

        document.getElementById('increaseQuantity').addEventListener('click', () => {
            quantityInput.value = parseInt(quantityInput.value) + 1;
            quantityDisplay.textContent = quantityInput.value;
            calculateTotal();
        });

        document.getElementById('decreaseQuantity').addEventListener('click', () => {
            if(parseInt(quantityInput.value) > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
                quantityDisplay.textContent = quantityInput.value;
                calculateTotal();
            }
        });

        document.getElementById('addToCartForm').addEventListener('submit', function() {
            localStorage.setItem('return_to_product', 'product-{{ $product->id }}');
            const overlay = document.getElementById('loading-overlay');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            
            overlay.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.remove('bg-brand-red', 'hover:bg-gray-900', 'shadow-[8px_8px_0px_0px_rgba(0,0,0,0.1)]');
            submitBtn.classList.add('bg-gray-800', 'cursor-not-allowed');
            if(btnText) btnText.innerText = 'Memproses...';
        });

        document.addEventListener('DOMContentLoaded', updateCrustOptions);
    </script>
@endsection

@push('styles')
<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
    .animate-spin { animation: spin 1s linear infinite; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    @media (max-width: 640px) { #loading-overlay .bg-white { width: 85%; padding: 2rem 1.5rem; } }
</style>
@endpush