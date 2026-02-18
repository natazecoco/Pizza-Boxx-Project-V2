@extends('layouts.customer')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

{{-- 1. HERO SECTION --}}
<section class="bg-brand-red pt-44 pb-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-pizza-pattern opacity-10"></div>
    
    {{-- Dekorasi Pizza Melayang --}}
    <img src="{{ asset('images/pizzabanner.png') }}" class="absolute -right-24 top-32 w-[500px] opacity-20 rotate-12 blur-sm pointer-events-none hidden lg:block">

    <div class="container mx-auto px-6 lg:px-12 relative z-10 text-center">
        <div class="inline-block px-4 py-1 bg-white/10 backdrop-blur-md border border-white/20 rounded-full mb-6">
            <span class="text-brand-kraft font-black text-xs uppercase tracking-[0.2em]">Get In Touch</span>
        </div>
        <h1 class="text-5xl md:text-7xl lg:text-9xl font-black text-white italic uppercase tracking-tighter leading-[0.85] drop-shadow-lg">
            HUBUNGI <span class="text-brand-kraft">KAMI.</span>
        </h1>
    </div>
</section>

{{-- 2. CONTACT CONTAINER --}}
<section class="py-24 bg-white relative z-20 -mt-10 rounded-t-[3rem] lg:rounded-t-[5rem] border-t-4 border-white">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="max-w-7xl mx-auto bg-white rounded-[3rem] lg:rounded-[4rem] shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] overflow-hidden border-4 border-gray-900 flex flex-col lg:flex-row">
            
            {{-- SISI KIRI: INFO (Dark Mode) --}}
            <div class="lg:w-2/5 bg-gray-900 p-10 lg:p-14 text-white relative overflow-hidden flex flex-col justify-between">
                <div class="absolute inset-0 bg-pizza-pattern opacity-[0.05]"></div>
                
                <div class="relative z-10">
                    <h2 class="text-4xl lg:text-5xl font-black italic uppercase tracking-tighter leading-none mb-6">
                        MARI <br><span class="text-brand-red">BERBINCANG.</span>
                    </h2>
                    <p class="text-gray-400 font-bold text-xs lg:text-sm leading-relaxed mb-10 tracking-wide border-l-4 border-brand-red pl-4">
                        Ada pertanyaan tentang menu kami atau ingin memberikan saran? Tim kami siap melayani Anda.
                    </p>

                    <div class="space-y-6">
                        {{-- Address Card --}}
                        <div class="bg-white/5 p-4 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors group">
                            <div class="flex gap-4 items-center">
                                <div class="w-10 h-10 bg-brand-red rounded-xl flex items-center justify-center flex-shrink-0 border-2 border-gray-900 shadow-sm group-hover:rotate-12 transition-transform">
                                    <i class="fas fa-map-marker-alt text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-brand-kraft uppercase tracking-widest mb-0.5">Alamat Kantor</p>
                                    <p class="text-xs font-bold italic uppercase leading-tight text-white">Jl. Pizza Raya No. 123, Sukahati, Depok, Jawa Barat</p>
                                </div>
                            </div>
                        </div>

                        {{-- Phone Card --}}
                        <div class="bg-white/5 p-4 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors group">
                            <div class="flex gap-4 items-center">
                                <div class="w-10 h-10 bg-brand-kraft rounded-xl flex items-center justify-center flex-shrink-0 border-2 border-gray-900 shadow-sm group-hover:-rotate-12 transition-transform">
                                    <i class="fas fa-phone-alt text-gray-900 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-brand-red uppercase tracking-widest mb-0.5">Telepon & WA</p>
                                    <p class="text-lg font-black italic uppercase text-white tracking-wider">(021) 123-4567</p>
                                </div>
                            </div>
                        </div>

                        {{-- Email Card --}}
                        <div class="bg-white/5 p-4 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors group">
                            <div class="flex gap-4 items-center">
                                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center flex-shrink-0 border-2 border-gray-900 shadow-sm group-hover:rotate-12 transition-transform">
                                    <i class="fas fa-envelope text-gray-900 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-brand-kraft uppercase tracking-widest mb-0.5">Email Support</p>
                                    <p class="text-xs font-bold italic uppercase text-white">halo@pizzaboxx.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Social Media --}}
                <div class="mt-12 relative z-10">
                    <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-4">Ikuti Perjalanan Kami</p>
                    <div class="flex gap-3">
                        @php
                            $socials = [
                                ['icon' => 'fab fa-instagram', 'link' => config('services.social.instagram')],
                                ['icon' => 'fab fa-tiktok', 'link' => config('services.social.tiktok')],
                            ];
                        @endphp

                        @foreach($socials as $social)
                            @if($social['link']) 
                                <a href="{{ $social['link'] }}" target="_blank" 
                                class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center text-white border border-gray-700 hover:bg-brand-red hover:border-brand-red hover:-translate-y-1 transition-all duration-300 group shadow-md">
                                    <i class="{{ $social['icon'] }} text-sm group-hover:animate-bounce"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- SISI KANAN: FORM (White) --}}
            <div class="lg:w-3/5 p-10 lg:p-14 bg-white">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-3xl lg:text-4xl font-black text-gray-900 italic uppercase tracking-tighter leading-none">KIRIM PESAN</h3>
                    <div class="hidden md:block px-3 py-1 bg-green-100 text-green-700 rounded-lg text-[9px] font-black uppercase tracking-widest border border-green-200">
                        <i class="fas fa-clock mr-1"></i> Respon < 24 Jam
                    </div>
                </div>

                <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- INPUT NAMA --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-900 ml-1">Nama Lengkap</label>
                            <input type="text" name="name" 
                                value="{{ old('name') }}" 
                                placeholder="Jhon Doe" 
                                class="w-full bg-slate-50 border-2 border-gray-200 p-4 rounded-xl focus:border-gray-900 focus:bg-white outline-none font-bold text-gray-900 text-sm transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,0.05)] focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] placeholder:text-gray-300">
                            @error('name')
                                <p class="text-[9px] text-brand-red font-black uppercase mt-1 ml-1 tracking-widest"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- INPUT EMAIL --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-900 ml-1">Alamat Email</label>
                            <input type="email" name="email" 
                                value="{{ old('email') }}" 
                                placeholder="email@anda.com" 
                                class="w-full bg-slate-50 border-2 border-gray-200 p-4 rounded-xl focus:border-gray-900 focus:bg-white outline-none font-bold text-gray-900 text-sm transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,0.05)] focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] placeholder:text-gray-300">
                            @error('email')
                                <p class="text-[9px] text-brand-red font-black uppercase mt-1 ml-1 tracking-widest"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- INPUT PESAN --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-900 ml-1">Pesan Anda</label>
                        <textarea name="message" rows="5" 
                            placeholder="Ceritakan apa yang bisa kami bantu..." 
                            class="w-full bg-slate-50 border-2 border-gray-200 p-4 rounded-xl focus:border-gray-900 focus:bg-white outline-none font-bold text-gray-900 text-sm transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,0.05)] focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] placeholder:text-gray-300 resize-none">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-[9px] text-brand-red font-black uppercase mt-1 ml-1 tracking-widest"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- TOMBOL SUBMIT --}}
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full bg-brand-red text-white font-black py-4 rounded-xl uppercase tracking-[0.2em] text-xs shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] border-2 border-transparent hover:bg-gray-900 hover:border-gray-900 hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all flex items-center justify-center gap-3 group">
                            <span>KIRIM SEKARANG</span>
                            <i class="fas fa-paper-plane text-lg group-hover:rotate-12 transition-transform duration-300"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- 3. GOOGLE MAPS EMBED --}}
<section class="pb-24 px-4 lg:px-12 bg-white">
    <div class="container mx-auto max-w-7xl">
        <div class="mb-8 flex items-end gap-4 border-b-4 border-gray-900 pb-4">
            <h3 class="text-3xl lg:text-5xl font-black text-gray-900 uppercase italic tracking-tighter leading-none">OUTLET <span class="text-brand-red">TERDEKAT</span></h3>
            <p id="nearest-info" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 ml-auto italic">
                Mencari lokasi Anda...
            </p>
        </div>

        {{-- Container Peta --}}
        <div class="p-2 bg-white border-4 border-gray-900 rounded-[3rem] shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]">
            <div id="contact-map" class="h-[400px] lg:h-[500px] w-full bg-slate-100 rounded-[2.5rem] overflow-hidden relative z-0 grayscale hover:grayscale-0 transition-all duration-700"></div>
        </div>
    </div>
</section>

<style>
    .bg-pizza-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 15l-5.5 11h11L30 15zm0-10l15 30H15L30 5z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
    }
    /* Custom Popup Leaflet */
    .leaflet-popup-content-wrapper {
        border-radius: 1rem;
        border: 2px solid #111827;
        box-shadow: 4px 4px 0px 0px rgba(0,0,0,1);
        padding: 0;
        overflow: hidden;
    }
    .leaflet-popup-content {
        margin: 12px 16px;
        font-family: 'Figtree', sans-serif;
    }
    .leaflet-popup-tip {
        background: #111827;
    }
    
    /* Custom Map Controls (Neo-Brutalist) */
    .custom-map-control {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-right: 12px;
        margin-bottom: 12px;
    }
    .map-btn {
        background-color: #ffffff;
        border: 2px solid #111827; /* Gray-900 */
        width: 40px;
        height: 40px;
        border-radius: 8px; /* Rounded kotak dikit */
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 4px 4px 0px 0px #000000;
        transition: all 0.2s ease;
        font-weight: 900;
        font-size: 18px;
        color: #111827;
    }
    .map-btn:hover {
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0px 0px #000000;
        background-color: #fef2f2; /* Red-50 */
        color: #DC2626; /* Brand-Red */
    }
    .map-btn:active {
        transform: translate(2px, 2px);
        box-shadow: 0px 0px 0px 0px #000000;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const outlets = @json($outlets ?? []); 
        
        // 1. Inisialisasi Peta (Matikan Zoom Control Bawaan)
        const map = L.map('contact-map', {
            zoomControl: false, // KITA BIKIN SENDIRI
            scrollWheelZoom: false 
        }).setView([-6.200000, 106.816666], 12);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap contributors',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);

        // --- CUSTOM CONTROLS (ZOOM & LOCATION) ---
        // Membuat container control baru di pojok kanan bawah
        const CustomControl = L.Control.extend({
            onAdd: function(map) {
                const container = L.DomUtil.create('div', 'custom-map-control');
                
                // Tombol Zoom In
                const zoomInBtn = L.DomUtil.create('button', 'map-btn', container);
                zoomInBtn.innerHTML = '<i class="fas fa-plus"></i>';
                zoomInBtn.onclick = (e) => { e.preventDefault(); map.zoomIn(); };

                // Tombol Zoom Out
                const zoomOutBtn = L.DomUtil.create('button', 'map-btn', container);
                zoomOutBtn.innerHTML = '<i class="fas fa-minus"></i>';
                zoomOutBtn.onclick = (e) => { e.preventDefault(); map.zoomOut(); };

                // Tombol Lokasi Saya
                const locateBtn = L.DomUtil.create('button', 'map-btn', container);
                locateBtn.innerHTML = '<i class="fas fa-crosshairs"></i>';
                locateBtn.title = "Lokasi Saya";
                locateBtn.onclick = (e) => { 
                    e.preventDefault(); 
                    locateUser(); // Panggil fungsi lokasi
                };

                return container;
            },
            onRemove: function(map) {}
        });

        // Tambahkan control ke peta (posisi bottomright)
        map.addControl(new CustomControl({ position: 'bottomright' }));


        // --- LOGIC MARKER & LOKASI ---
        const pizzaIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="background-color: #EF4444; width: 32px; height: 32px; border-radius: 50%; border: 2px solid #111827; display: flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0px 0px rgba(0,0,0,1); transition: transform 0.2s;"><i class="fas fa-pizza-slice text-white text-xs"></i></div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -36]
        });

        const markers = [];
        let userMarker = null; // Simpan marker user biar bisa diupdate

        if(outlets.length > 0) {
            outlets.forEach(outlet => {
                const gmapsUrl = outlet.maps_url 
                    ? outlet.maps_url 
                    : `https://www.google.com/maps/dir/?api=1&destination=${outlet.latitude},${outlet.longitude}`;

                const popupContent = `
                    <div style="text-align: left; min-width: 160px;">
                        <b class="uppercase italic text-brand-red text-sm tracking-tight block mb-1">${outlet.name}</b>
                        <span class="text-[10px] font-bold text-gray-500 tracking-wide leading-tight block mb-3">${outlet.address}</span>
                        <a href="${gmapsUrl}" target="_blank" 
                           class="block w-full bg-gray-900 text-white text-[9px] font-black uppercase tracking-widest px-3 py-2 rounded-lg text-center hover:bg-brand-red transition-colors border-2 border-transparent hover:border-gray-900 shadow-md" 
                           style="text-decoration: none; color: white;">
                            <i class="fas fa-location-arrow mr-1"></i> Rute ke Sini
                        </a>
                    </div>
                `;

                const m = L.marker([outlet.latitude, outlet.longitude], {icon: pizzaIcon})
                    .addTo(map)
                    .bindPopup(popupContent);
                
                markers.push({ data: outlet, marker: m });
            });
        }

        // Fungsi Cari Lokasi User (Dipisah biar bisa dipanggil tombol)
        function locateUser() {
            const infoEl = document.getElementById('nearest-info');
            if(infoEl) infoEl.innerHTML = "<span class='text-gray-500 italic animate-pulse'>Sedang melacak posisi...</span>";

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const userPos = [position.coords.latitude, position.coords.longitude];
                    
                    // Icon User (Biru)
                    const userIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div style="background-color: #3B82F6; width: 24px; height: 24px; border-radius: 50%; border: 2px solid #111827; box-shadow: 2px 2px 0px 0px rgba(0,0,0,1);"></div>`,
                        iconSize: [24, 24],
                        iconAnchor: [12, 12]
                    });
                    
                    // Update Marker User
                    if (userMarker) {
                        userMarker.setLatLng(userPos);
                    } else {
                        userMarker = L.marker(userPos, {icon: userIcon}).addTo(map).bindPopup("<b class='text-xs uppercase font-black'>Lokasi Kamu</b>");
                    }

                    // Cari Outlet Terdekat
                    if(markers.length > 0) {
                        let closest = null;
                        let minSub = Infinity;

                        markers.forEach(item => {
                            let d = Math.sqrt(Math.pow(userPos[0] - item.data.latitude, 2) + Math.pow(userPos[1] - item.data.longitude, 2));
                            if (d < minSub) { minSub = d; closest = item; }
                        });

                        if (closest) {
                            // Terbang ke titik tengah antara User dan Outlet (Biar kelihatan dua-duanya)
                            const bounds = L.latLngBounds([userPos, [closest.data.latitude, closest.data.longitude]]);
                            map.fitBounds(bounds, { padding: [50, 50], animate: true });
                            
                            setTimeout(() => closest.marker.openPopup(), 1000);
                            
                            if(infoEl) {
                                infoEl.innerHTML = 
                                    `<span class="bg-green-100 text-green-700 px-2 py-1 rounded border border-green-300 mr-1 text-[9px] font-black"><i class="fas fa-check-circle"></i> KETEMU</span> <span class="text-gray-900 font-black italic text-xs">Dekat ${closest.data.name}</span>`;
                            }
                        }
                    } else {
                        // Kalau ga ada outlet, zoom ke user aja
                        map.setView(userPos, 15);
                        if(infoEl) infoEl.innerHTML = "Lokasi ditemukan.";
                    }

                }, (error) => {
                    console.error("GPS Error:", error);
                    if(infoEl) infoEl.innerHTML = "<span class='text-brand-red font-bold'>Gagal mendeteksi lokasi (GPS Off).</span>";
                });
            } else {
                 if(infoEl) infoEl.innerHTML = "Browser tidak support GPS.";
            }
        }

        // Jalankan sekali saat load
        locateUser();
    });
</script>
@endsection