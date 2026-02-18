@extends('layouts.customer')

@section('content')

    {{-- HERO HEADER --}}
    <section class="bg-brand-red pt-32 pb-12 lg:pt-44 lg:pb-16 relative overflow-hidden rounded-b-[2.5rem] lg:rounded-b-[4rem] shadow-[0px_12px_0px_0px_rgba(0,0,0,0.1)] z-10 border-b-4 border-gray-900">
        <div class="absolute inset-0 bg-pizza-pattern opacity-10"></div>
        {{-- Dekorasi Pizza Melayang (Biar konsisten sama halaman lain) --}}
        <img src="{{ asset('images/pizzabanner.png') }}" class="absolute -right-24 top-20 w-[400px] opacity-20 rotate-12 blur-sm pointer-events-none hidden lg:block">

        <div class="container mx-auto px-6 text-center relative z-10">
            <h1 class="text-4xl lg:text-7xl font-black text-white italic uppercase tracking-tighter leading-none mb-2 drop-shadow-md">
                KERANJANG <span class="text-brand-kraft">PIZZA</span>
            </h1>
            <p class="text-white/80 font-bold uppercase tracking-[0.3em] text-[10px] lg:text-xs bg-gray-900/20 inline-block px-4 py-1 rounded-full border border-white/10">Cek lagi sebelum dibungkus!</p>
        </div>
    </section>

    {{-- KONTEN UTAMA --}}
    <div class="bg-slate-50 min-h-screen pb-12 lg:pb-24 pt-8 lg:pt-16">
        <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
            
            @if(empty($cart))
                {{-- EMPTY STATE (Neo-Brutalist) --}}
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="bg-white rounded-[3rem] p-12 lg:p-16 shadow-[12px_12px_0px_0px_rgba(0,0,0,0.05)] border-4 border-dashed border-gray-200 text-center max-w-xl mx-auto w-full group hover:border-gray-900 transition-colors duration-300">
                        <div class="w-28 h-28 bg-red-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8 rotate-3 border-4 border-red-100 group-hover:bg-brand-red group-hover:border-gray-900 transition-colors duration-300">
                            <i class="fas fa-pizza-slice text-5xl text-brand-red group-hover:text-white animate-bounce"></i>
                        </div>
                        <h2 class="text-3xl lg:text-4xl font-black text-gray-900 uppercase italic tracking-tighter mb-4 leading-none">Masih Kosong Nih!</h2>
                        <p class="text-gray-400 font-bold text-sm mb-8 tracking-wide">Perut kenyang hati senang, yuk pilih pizza favoritmu sekarang.</p>
                        
                        <a href="{{ route('menu.index') }}" class="inline-flex bg-gray-900 text-white font-black py-4 px-10 rounded-2xl text-xs uppercase tracking-[0.2em] transition-all hover:-translate-y-1 hover:shadow-[6px_6px_0px_0px_rgba(220,38,38,1)] border-2 border-transparent">
                            Cari Pizza <i class="fas fa-search ml-2"></i>
                        </a>
                    </div>
                </div>
            @else
                <div class="flex flex-col lg:flex-row gap-8 items-start relative">
                    
                    {{-- LIST ITEM (KIRI) --}}
                    <div class="w-full lg:w-[65%] space-y-4 lg:space-y-6">
                        @foreach($cart as $itemKey => $item)
                        <div class="bg-white rounded-[2.5rem] p-4 lg:p-6 shadow-sm border-2 border-transparent hover:border-gray-900 hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 transition-all duration-300 relative group overflow-hidden">
                            {{-- Background Decoration --}}
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-bl-[6rem] -z-0 transition-colors group-hover:bg-brand-red/10"></div>
                            
                            <div class="flex gap-4 lg:gap-6 relative z-10">
                                <div class="w-24 h-24 lg:w-36 lg:h-36 flex-shrink-0">
                                    <img src="{{ asset('storage/' . $item['image_path']) }}" class="w-full h-full object-cover rounded-2xl shadow-md border-2 border-gray-100 group-hover:border-gray-900 transition-colors">
                                </div>
                                <div class="flex-grow flex flex-col justify-between py-1">
                                    <div class="mb-3">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="text-lg lg:text-2xl font-black text-gray-900 uppercase italic leading-none mb-2 group-hover:text-brand-red transition-colors">{{ $item['name'] }}</h3>
                                                <div class="flex flex-wrap gap-1.5 mb-2">
                                                    @if($item['size_option']) <span class="px-2 py-0.5 rounded-lg bg-gray-100 border border-gray-200 text-[9px] font-black text-gray-500 uppercase tracking-wider">{{ $item['size_option']['name'] }}</span> @endif
                                                    @if($item['crust_option']) <span class="px-2 py-0.5 rounded-lg bg-gray-100 border border-gray-200 text-[9px] font-black text-gray-500 uppercase tracking-wider">{{ $item['crust_option']['name'] }}</span> @endif
                                                </div>
                                            </div>
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="item_key" value="{{ $itemKey }}">
                                                <button type="submit" class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-300 hover:text-white hover:bg-brand-red transition-all active:scale-90" title="Hapus"><i class="fas fa-trash-alt text-sm"></i></button>
                                            </form>
                                        </div>
                                        @if(!empty($item['addons']))
                                            <div class="mt-1">
                                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-0.5">Extra Topping:</p>
                                                <p class="text-[10px] text-gray-600 font-bold leading-tight line-clamp-2">+ {{ collect($item['addons'])->pluck('name')->implode(', ') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col-reverse sm:flex-row sm:items-end justify-between gap-3 mt-auto">
                                        <div class="flex items-center bg-gray-50 p-1.5 rounded-xl border border-gray-200 w-max group-hover:border-gray-900 transition-colors">
                                            <form action="{{ route('cart.update') }}" method="POST">
                                                @csrf <input type="hidden" name="item_key" value="{{ $itemKey }}"> <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                                                <button type="submit" class="w-7 h-7 lg:w-8 lg:h-8 flex items-center justify-center text-gray-400 hover:text-gray-900 hover:bg-white font-black text-sm hover:scale-110 active:scale-95 rounded-lg transition-all">-</button>
                                            </form>
                                            <span class="text-sm lg:text-base font-black text-gray-900 italic w-8 text-center">{{ $item['quantity'] }}</span>
                                            <form action="{{ route('cart.update') }}" method="POST">
                                                @csrf <input type="hidden" name="item_key" value="{{ $itemKey }}"> <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                                <button type="submit" class="w-7 h-7 lg:w-8 lg:h-8 flex items-center justify-center text-white bg-gray-900 hover:bg-brand-red font-black text-sm hover:scale-110 active:scale-95 rounded-lg shadow-sm transition-all">+</button>
                                            </form>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg lg:text-2xl font-black text-brand-red italic tracking-tighter">Rp {{ number_format($item['total_price'], 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        {{-- MOBILE STICKY TOTAL --}}
                        <div class="sticky bottom-4 z-40 mx-2 mb-2 bg-white/95 backdrop-blur-md rounded-[2rem] border-2 border-gray-900 p-5 lg:hidden shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex flex-col pl-2">
                                    <span class="text-[9px] font-black uppercase text-gray-400 tracking-widest mb-0.5">Total Bayar</span>
                                    <span class="text-2xl font-black text-brand-red italic tracking-tighter leading-none">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                                </div>
                                @auth
                                    <a href="{{ route('checkout.index') }}" 
                                    class="group bg-gray-900 hover:bg-brand-red text-white font-black py-3 px-6 rounded-2xl uppercase tracking-widest text-[10px] shadow-[2px_2px_0px_0px_rgba(255,255,255,0.3)] border-2 border-transparent transition-all duration-200 flex items-center gap-2">
                                        Checkout <i class="fas fa-arrow-right text-[8px] group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                @else
                                    <button type="button" onclick="showLoginRequiredAlert()"
                                    class="group bg-gray-900 hover:bg-brand-red text-white font-black py-3 px-6 rounded-2xl uppercase tracking-widest text-[10px] shadow-[2px_2px_0px_0px_rgba(255,255,255,0.3)] border-2 border-transparent transition-all duration-200 flex items-center gap-2">
                                        Checkout <i class="fas fa-lock text-[8px]"></i>
                                    </button>
                                @endauth
                            </div>
                        </div>
                        <div class="h-4 lg:hidden"></div>
                    </div>

                    {{-- SUMMARY BOX (DESKTOP) --}}
                    <div class="hidden lg:block w-[35%] sticky top-32">
                        <div class="bg-gray-900 rounded-[2.5rem] p-8 shadow-[12px_12px_0px_0px_rgba(0,0,0,0.3)] relative overflow-hidden border-4 border-gray-900">
                            <div class="absolute inset-0 bg-pizza-pattern opacity-[0.05]"></div>
                            <div class="relative z-10">
                                <div class="flex justify-between items-center mb-8 pb-6 border-b border-gray-800">
                                    <h3 class="text-white font-black uppercase italic tracking-tighter text-2xl">Ringkasan</h3>
                                    <span class="bg-brand-red text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-lg border-2 border-gray-900 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">{{ count($cart) }} Items</span>
                                </div>
                                <div class="flex justify-between items-end mb-2">
                                    <span class="text-gray-400 text-xs font-bold uppercase tracking-widest">Subtotal</span>
                                    <span class="text-3xl font-black text-white italic tracking-tighter">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-10">
                                    <span class="text-gray-600 text-[10px] font-bold uppercase tracking-widest bg-gray-800 px-2 py-1 rounded">*Belum termasuk ongkir</span>
                                </div>
                                @auth
                                    <a href="{{ route('checkout.index') }}" 
                                    class="group w-full bg-brand-red hover:bg-white text-white hover:text-gray-900 font-black py-5 px-8 rounded-[1.5rem] uppercase tracking-[0.2em] text-sm shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all duration-200 flex items-center justify-center gap-3 border-2 border-transparent hover:border-gray-900">
                                        <span>Checkout</span>
                                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                @else
                                    <button type="button" onclick="showLoginRequiredAlert()"
                                    class="group w-full bg-brand-red hover:bg-white text-white hover:text-gray-900 font-black py-5 px-8 rounded-[1.5rem] uppercase tracking-[0.2em] text-sm shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all duration-200 flex items-center justify-center gap-3 border-2 border-transparent hover:border-gray-900">
                                        <span>Checkout</span>
                                        <i class="fas fa-lock"></i>
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ALERT LOGIN REQUIRED --}}
    <script>
        function showLoginRequiredAlert() {
            Swal.fire({
                title: 'PESAN PIZZA JADI MUDAH!',
                text: 'Login dulu yuk biar alamat dan riwayat pesananmu tersimpan rapi.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'LOGIN SEKARANG',
                cancelButtonText: 'NANTI DULU',
                customClass: {
                    popup: 'rounded-[3rem] border-4 border-gray-900 shadow-2xl p-8',
                    title: 'font-black uppercase italic text-gray-900 tracking-tighter text-2xl mb-4',
                    htmlContainer: 'font-bold text-gray-500 text-[11px] uppercase tracking-widest leading-relaxed',
                    confirmButton: 'bg-gray-900 text-white font-black uppercase italic px-8 py-4 rounded-2xl mx-2 hover:bg-brand-red transition-all text-[10px] tracking-[0.2em] shadow-[4px_4px_0px_0px_rgba(220,38,38,1)]',
                    cancelButton: 'bg-gray-200 text-gray-500 font-black uppercase italic px-8 py-4 rounded-2xl mx-2 text-[10px] tracking-[0.2em]'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                }
            });
        }
    </script>    
@endsection