@extends('layouts.customer')

@section('content')
    <!-- {{-- Toast Notification --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" 
         class="fixed top-24 right-6 z-[100] animate-fade-in-down">
        <div class="bg-gray-900 text-white px-6 py-4 rounded-2xl shadow-[8px_8px_0px_0px_rgba(220,38,38,1)] flex items-center gap-4 border-2 border-white">
            <i class="fas fa-check-circle text-brand-kraft text-xl"></i>
            <p class="text-xs font-black uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    </div>
    @endif -->

    {{-- 1. HERO SECTION --}}
    <section class="bg-brand-red pt-44 pb-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-pizza-pattern opacity-10"></div>
        
        {{-- Dekorasi Pizza Melayang (Parallax Simple) --}}
        <img src="{{ asset('images/pizzabanner.png') }}" class="absolute -right-24 top-20 w-[600px] opacity-20 rotate-12 blur-sm pointer-events-none hidden lg:block">

        <div class="container mx-auto px-6 lg:px-12 relative z-10 text-center">
            <div class="inline-block px-4 py-1 bg-white/10 backdrop-blur-md border border-white/20 rounded-full mb-4">
                <span class="text-brand-kraft font-black text-xs uppercase tracking-[0.2em]">Our Secret Recipes</span>
            </div>
            <h1 class="text-5xl md:text-7xl lg:text-9xl font-black text-white italic uppercase tracking-tighter leading-[0.85] drop-shadow-lg">
                PILIH <br><span class="text-brand-kraft">FAVORITMU</span>
            </h1>
        </div>
    </section>

    {{-- 2. CONTENT WRAPPER --}}
    <section class="py-12 bg-white relative z-20 -mt-10 rounded-t-[3rem] lg:rounded-t-[5rem] min-h-screen border-t-4 border-white">
        <div class="container mx-auto px-4 lg:px-12">
            
            {{-- 2.1 DELIVERY STATUS (Style Alert Box) --}}
            <div id="delivery-status-message" class="bg-brand-kraft rounded-[2rem] p-6 mb-12 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] border-4 border-gray-900 flex flex-col md:flex-row items-center justify-between gap-6 transition-all duration-500 max-w-5xl mx-auto relative overflow-hidden">
                <div class="flex items-center gap-5 text-center md:text-left relative z-10">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center border-2 border-gray-900 shadow-sm animate-pulse">
                        <i class="fas fa-map-marker-alt text-brand-red text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-brand-red bg-white px-2 py-0.5 rounded-md inline-block border border-gray-200 mb-1">Status Lokasi</p>
                        <p class="text-base font-black text-gray-900 uppercase italic leading-none">Sedang melacak posisi Anda...</p>
                    </div>
                </div>
                {{-- Progress Bar --}}
                <div class="h-3 w-full md:w-48 bg-white border-2 border-gray-900 rounded-full overflow-hidden relative z-10">
                    <div class="h-full bg-brand-red animate-progress"></div>
                </div>
                {{-- Background Pattern --}}
                <div class="absolute inset-0 bg-pizza-pattern opacity-5"></div>
            </div>

            {{-- 2.2 KATEGORI NAVIGASI (Sticky & Hard Style) --}}
            <div class="sticky top-4 z-40 transition-all duration-500 ease-in-out">
                
                {{-- Navbar Kategori (Existing) --}}
                <div id="category-navbar-pill" class="bg-white/90 backdrop-blur-lg py-3 px-2 mb-12 max-w-max mx-auto border-2 border-gray-900 rounded-full shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] relative z-40">
                    <div class="flex gap-2 overflow-x-auto no-scrollbar lg:justify-center px-2" id="category-nav">
                        @foreach($categoriesWithProducts as $index => $category)
                            <a href="#category-{{ $category->id }}"
                            class="nav-item px-6 py-3 rounded-full font-black text-[10px] lg:text-xs uppercase tracking-widest transition-all whitespace-nowrap border-2 snap-start
                                    {{ $index === 0 
                                        ? 'bg-brand-red text-white border-gray-900 shadow-md' 
                                        : 'bg-white text-gray-500 border-transparent hover:border-gray-200 hover:text-red-400' }}"
                            data-target="category-{{ $category->id }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- TOMBOL PENARIK NAVBAR UTAMA (Baru) --}}
                <button id="main-nav-trigger" onclick="toggleMainNavbar()" class="pull-tab shadow-md flex items-center gap-2 font-bold uppercase tracking-widest border-2 border-gray-900 border-b-0">
                    <i class="fas fa-chevron-down animate-bounce"></i> Menu Utama
                </button>
            </div>

            {{-- 2.3 LIST MENU --}}
            <div class="space-y-20 pb-24">
                @foreach($categoriesWithProducts as $category)
                    {{-- Scroll Offset --}}
                    <div id="category-{{ $category->id }}" class="scroll-mt-[110px] lg:scroll-mt-[20px] category-section">    
                        
                        {{-- Section Header --}}
                        <div class="section-border flex items-end gap-4 mb-10 border-b-4 border-gray-900 pb-4 transition-colors duration-300">
                            <h2 class="text-4xl lg:text-6xl font-black text-gray-900 uppercase italic tracking-tighter leading-none transition-colors duration-300 hover:text-brand-red">
                                {{ $category->name }}
                            </h2>
                            <span class="item-count text-xs font-bold text-gray-400 uppercase tracking-widest mb-1 ml-auto transition-colors duration-300">
                                {{ $category->products->count() }} Items
                            </span>
                        </div>

                        {{-- Product Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">
                            @foreach($category->products as $product)
                                {{-- CARD PRODUCT (Style Neo-Brutalist) --}}
                                <div id="product-{{ $product->id }}" class="group relative bg-slate-50 rounded-[2rem] p-4 border-2 border-transparent hover:border-gray-900 hover:bg-white hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                                    
                                    {{-- Image --}}
                                    <div class="relative h-48 lg:h-56 mb-5 overflow-hidden rounded-[1.5rem] bg-white border-2 border-gray-100 group-hover:border-gray-900 transition-colors">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" 
                                                 loading="lazy" alt="{{ $product->name }}"
                                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                        @else
                                            <div class="w-full h-full bg-gray-50 flex flex-col items-center justify-center text-gray-300">
                                                <i class="fas fa-pizza-slice text-4xl mb-2 opacity-50"></i>
                                                <span class="text-[10px] font-black uppercase">No Image</span>
                                            </div>
                                        @endif
                                        
                                        @if($product->is_best_seller)
                                            <div class="absolute top-3 left-3 bg-brand-red text-white text-[9px] font-black px-3 py-1 rounded-md border-2 border-brand-red shadow-sm uppercase tracking-widest z-10">
                                                Best Seller
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Content --}}
                                    <div class="flex flex-col flex-grow px-2">
                                        <h3 class="text-xl font-black text-gray-900 uppercase italic leading-none mb-2 group-hover:text-brand-red transition-colors">
                                            {{ $product->name }}
                                        </h3>
                                        <p class="text-gray-500 text-[11px] font-bold leading-relaxed mb-4 line-clamp-2 h-8">
                                            {{ $product->description ?? 'Nikmati sensasi rasa otentik.' }}
                                        </p>

                                        {{-- Footer Card --}}
                                        <div class="mt-auto flex items-end justify-between border-t-2 border-dashed border-gray-200 pt-4 group-hover:border-gray-900 transition-colors">
                                            <div>
                                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Harga</p>
                                                <span class="text-brand-red font-black text-lg lg:text-xl italic">
                                                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                                </span>
                                            </div>

                                            <a href="{{ route('menu.show', $product->id) }}" 
                                               class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-gray-900 border-2 border-gray-200 hover:border-gray-900 hover:bg-gray-900 hover:text-white transition-all shadow-sm group/btn">
                                                <i class="fas fa-plus text-xs group-hover/btn:rotate-90 transition-transform"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        @keyframes progress { 0% { width: 0%; } 100% { width: 100%; } }
        .animate-progress { animation: progress 2s ease-in-out infinite; }
        html { scroll-behavior: smooth; }

        /* Keadaan Navbar Utama Tersembunyi (Naik ke atas layar) */
        .navbar-hidden {
            transform: translateY(-150%);
            transition: transform 0.4s ease-in-out;
        }
        
        /* Keadaan Navbar Utama Muncul */
        .navbar-visible {
            transform: translateY(0);
            transition: transform 0.4s ease-in-out;
        }

        /* Tombol Penarik (Pull Tab) */
        /* Tombol Penarik (Versi Gantung di Bawah) */
        .pull-tab {
            position: absolute;
            /* Pindah ke bawah container */
            top: 100%; 
            left: 50%;
            transform: translateX(-50%);
            
            /* Styling Neo-Brutalist */
            background-color: #111827; /* Gray-900 */
            color: white;
            padding: 6px 20px;
            
            /* Rounded Bawah (Seperti lidah/tag) */
            border-radius: 0 0 16px 16px; 
            
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            cursor: pointer;
            
            /* Transisi */
            opacity: 0;
            margin-top: -10px; /* Awalnya ngumpet sedikit di belakang navbar */
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); /* Efek membal */
            
            z-index: 30; /* Di bawah navbar kategori (z-40) tapi di atas konten */
            pointer-events: none;
        }

        /* Saat Aktif (Navbar Utama Hilang) */
        .pull-tab.active {
            opacity: 1;
            margin-top: -2px; /* Turun ke posisi gantung yang pas (dikurangi border) */
            pointer-events: auto;
        }
    </style>
    @endpush

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- 1. LOGIKA LOKASI & STATUS (Tetap) ---
        const branchLocationData = @json($branchLocationData);
        const statusBox = document.getElementById('delivery-status-message');

        function updateStatusUI(icon, title, message, colorType) {
            const colors = {
                green: { bg: 'bg-emerald-50', border: 'border-emerald-500', text: 'text-emerald-600', iconBg: 'bg-emerald-100' },
                yellow: { bg: 'bg-brand-kraft', border: 'border-gray-900', text: 'text-gray-900', iconBg: 'bg-white' },
                red: { bg: 'bg-red-50', border: 'border-brand-red', text: 'text-brand-red', iconBg: 'bg-red-100' }
            };
            const c = colors[colorType] || colors.yellow;
            
            if(statusBox) {
                // 1. Update Tampilan Container
                statusBox.className = `${c.bg} rounded-[2rem] p-6 mb-12 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] border-4 ${c.border} flex flex-col md:flex-row items-center justify-between gap-6 transition-all duration-500 max-w-5xl mx-auto relative overflow-hidden`;
                
                // 2. Update Isi Konten
                const contentDiv = statusBox.querySelector('.flex');
                if(contentDiv) {
                    contentDiv.innerHTML = `
                        <div class="w-14 h-14 ${c.iconBg} rounded-2xl flex items-center justify-center border-2 border-gray-900 shadow-sm transition-colors duration-500">
                            <i class="fas ${icon} ${c.text} text-2xl transition-colors duration-500"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest ${c.text} bg-white/50 px-2 py-0.5 rounded-md inline-block border border-gray-200/50 mb-1 transition-colors duration-500">${title}</p>
                            <p class="text-base font-black text-gray-900 uppercase italic leading-none">${message}</p>
                        </div>
                    `;
                }

                // 3. LOGIKA PROGRESS BAR (SMOOTH FILL)
                const progressBarWrapper = statusBox.querySelector('.h-3');
                const progressBar = progressBarWrapper ? progressBarWrapper.firstElementChild : null;

                if(progressBar) {
                    if (colorType === 'yellow') {
                        // --- SEDANG LOADING ---
                        // Reset ke animasi looping
                        progressBar.style.width = 'auto'; 
                        progressBar.style.transition = 'none'; // Matikan transisi biar animasi loop jalan
                        progressBar.className = "h-full bg-brand-red animate-progress";
                    } else {
                        // --- SELESAI (HIJAU/MERAH) ---
                        
                        // Langkah A: Matikan animasi loop, tapi pertahankan warna merah dulu
                        progressBar.classList.remove('animate-progress');
                        
                        // Langkah B: Aktifkan transisi halus (1 detik)
                        // Kita set width manual biar CSS transition bisa menangkap perubahan
                        progressBar.style.transition = 'width 1s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.5s ease-in';
                        progressBar.style.width = '100%'; // Penuhi bar
                        
                        // Langkah C: Ganti warna (Delay dikit biar merahnya penuh dulu baru jadi hijau)
                        setTimeout(() => {
                            if(colorType === 'green') {
                                progressBar.classList.remove('bg-brand-red');
                                progressBar.classList.add('bg-emerald-500');
                            } else if (colorType === 'red') {
                                // Tetap merah tapi penuh
                                progressBar.classList.add('bg-brand-red');
                            }
                        }, 2000); // Delay 2 detik agar mata melihat bar penuh dulu
                    }
                }
            }
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (p) => updateStatusUI('fa-check-circle', 'Lokasi Terdeteksi', branchLocationData ? `Outlet: ${branchLocationData.name}` : 'Siap memesan!', 'green'),
                (e) => updateStatusUI('fa-map-marker-alt', 'Menunggu Lokasi', 'Sedang mencari lokasi Anda...', 'yellow')
            );
        } else {
             updateStatusUI('fa-exclamation-triangle', 'GPS Error', 'Browser tidak mendukung GPS', 'red');
        }

        // --- 2. SCROLLSPY (Updated: Pills + Judul + Garis + Count) ---
        const navLinks = document.querySelectorAll('.nav-item');
        const sections = document.querySelectorAll('.category-section');

        // Ambil semua elemen yang mau diwarnai
        const allHeadings = document.querySelectorAll('.category-section h2');
        const allBorders = document.querySelectorAll('.section-border');
        const allCounts = document.querySelectorAll('.item-count');

        const observerOptions = {
            root: null,
            rootMargin: '-20% 0px -70% 0px', 
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    
                    // ---------------------------------------------
                    // A. UPDATE NAVBAR PILLS
                    // ---------------------------------------------
                    navLinks.forEach(link => {
                        link.className = "nav-item px-6 py-3 rounded-full font-black text-[10px] lg:text-xs uppercase tracking-widest transition-all whitespace-nowrap border-2 bg-white text-gray-400 border-transparent hover:border-gray-200 hover:text-gray-900";
                    });

                    const activeLink = document.querySelector(`.nav-item[data-target="${entry.target.id}"]`);
                    if (activeLink) {
                        activeLink.className = "nav-item px-6 py-3 rounded-full font-black text-[10px] lg:text-xs uppercase tracking-widest transition-all whitespace-nowrap border-2 bg-brand-red text-white border-brand-red shadow-md transform scale-105";
                        activeLink.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                    }

                    // ---------------------------------------------
                    // B. RESET SEMUA KE WARNA AWAL (HITAM/ABU)
                    // ---------------------------------------------
                    // 1. Reset Judul (Jadi Hitam)
                    allHeadings.forEach(el => {
                        el.classList.remove('text-brand-red');
                        el.classList.add('text-gray-900');
                    });
                    // 2. Reset Garis (Jadi Hitam)
                    allBorders.forEach(el => {
                        el.classList.remove('border-brand-red');
                        el.classList.add('border-gray-900');
                    });
                    // 3. Reset Jumlah Item (Jadi Abu-abu)
                    allCounts.forEach(el => {
                        el.classList.remove('text-brand-red');
                        el.classList.add('text-gray-400');
                    });

                    // ---------------------------------------------
                    // C. UBAH SECTION AKTIF KE MERAH
                    // ---------------------------------------------
                    const activeHeading = entry.target.querySelector('h2');
                    const activeBorder = entry.target.querySelector('.section-border');
                    const activeCount = entry.target.querySelector('.item-count');

                    if (activeHeading) {
                        activeHeading.classList.remove('text-gray-900');
                        activeHeading.classList.add('text-brand-red');
                    }
                    if (activeBorder) {
                        activeBorder.classList.remove('border-gray-900');
                        activeBorder.classList.add('border-brand-red');
                    }
                    if (activeCount) {
                        activeCount.classList.remove('text-gray-400');
                        activeCount.classList.add('text-brand-red');
                    }
                }
            });
        }, observerOptions);

        sections.forEach(section => observer.observe(section));

        // --- 3. LOGIKA HIDE/SHOW NAVBAR UTAMA (NEW) ---
        const mainNav = document.getElementById('main-navbar-pill'); // Pastikan ID ini ada di layout utama
        const triggerBtn = document.getElementById('main-nav-trigger');
        const categoryNavWrapper = document.getElementById('category-navbar-pill').parentElement; // Div sticky pembungkus
        
        let lastScrollTop = 0;
        let isMainNavbarVisible = true;

        // Fungsi Toggle Manual (Saat tombol diklik)
        window.toggleMainNavbar = function() {
            if (!mainNav) return;
            
            if (isMainNavbarVisible) {
                // Sembunyikan
                mainNav.classList.add('navbar-hidden');
                mainNav.classList.remove('navbar-visible');
                triggerBtn.classList.add('active');
                isMainNavbarVisible = false;
            } else {
                // Munculkan
                mainNav.classList.remove('navbar-hidden');
                mainNav.classList.add('navbar-visible');
                triggerBtn.classList.remove('active');
                isMainNavbarVisible = true;
            }
        };

        window.addEventListener('scroll', () => {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Jangan jalankan kalau scroll masih di paling atas (Hero Section)
            // Biarkan navbar utama terlihat saat di Hero
            if (scrollTop < 300) {
                if (!isMainNavbarVisible) {
                    toggleMainNavbar(); // Munculkan kembali otomatis kalau user scroll ke paling atas
                }
                return;
            }

            // Deteksi Scroll ke Bawah vs Atas
            if (scrollTop > lastScrollTop) {
                // Scroll ke BAWAH -> Sembunyikan Navbar Utama
                if (isMainNavbarVisible) {
                    mainNav.classList.add('navbar-hidden');
                    mainNav.classList.remove('navbar-visible');
                    triggerBtn.classList.add('active');
                    
                    // Ubah posisi sticky category nav jadi lebih ke atas
                    // categoryNavWrapper.style.top = "1rem"; 
                    isMainNavbarVisible = false;
                }
            } 
            // Opsional: Kalau mau otomatis muncul pas scroll ke atas, uncomment block di bawah ini.
            // Tapi requestmu pakai tombol kan? Jadi blok ini bisa diskip biar user fokus ke tombol.
            /* else {
                // Scroll ke ATAS -> Munculkan Navbar Utama
                if (!isMainNavbarVisible) {
                    mainNav.classList.remove('navbar-hidden');
                    mainNav.classList.add('navbar-visible');
                    triggerBtn.classList.remove('active');
                    isMainNavbarVisible = true;
                }
            }
            */
            
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // For Mobile or negative scrolling
        });

        // ============================================================
        // --- 4. TAMBAHAN: SCROLL MEMORY & HIGHLIGHT (Taruh di Sini) ---
        // ============================================================
        const targetProductId = localStorage.getItem('return_to_product');
        
        if (targetProductId) {
            // Kasih delay dikit biar browser selesai render layouting-nya
            setTimeout(() => {
                const element = document.getElementById(targetProductId);
                if (element) {
                    // Scroll ke produk tersebut
                    element.scrollIntoView({ behavior: 'smooth', block: 'center' });

                    // Kasih efek "nyala" (Ring) biar user tau itu produk barusan
                    // Kita pakai ring merah biar sesuai tema Neo-Brutalist
                    element.classList.add('ring-4', 'ring-brand-red', 'ring-offset-4', 'transition-all', 'duration-500');
                    
                    // Hilangkan efek ring setelah 2 detik
                    setTimeout(() => {
                        element.classList.remove('ring-4', 'ring-brand-red', 'ring-offset-4');
                    }, 2500);
                }
                // Hapus memory-nya biar gak scroll terus setiap refresh halaman
                localStorage.removeItem('return_to_product');
            }, 800); // Delay 800ms cukup aman agar peta/gambar selesai dimuat
        }
    });
    </script>
@endsection