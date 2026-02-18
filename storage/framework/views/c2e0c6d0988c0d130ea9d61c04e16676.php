<?php $__env->startSection('title', 'Profile Saya'); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #111827; border-radius: 4px; }
        
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        .animate-pop-in { animation: popIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes popIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

        #map { height: 250px !important; width: 100% !important; z-index: 1; }
        .map-wrapper { border: 2px solid #111827; border-radius: 1rem; overflow: hidden; }
    </style>
<?php $__env->stopPush(); ?>


<section class="bg-brand-red pt-32 pb-24 relative overflow-hidden rounded-b-[3rem] shadow-xl z-10">
    <div class="absolute inset-0 bg-pizza-pattern opacity-10"></div>
    <div class="container mx-auto px-6 lg:px-12 relative z-10 text-center">
        <span class="text-brand-kraft font-black text-xs uppercase tracking-widest animate-fade-in">Personal Dashboard</span>
        <h1 class="text-5xl md:text-7xl font-black text-white italic uppercase tracking-tighter leading-none mt-4 mb-4">
            AKUN <span class="text-brand-kraft">SAYA</span>
        </h1>
        
        <div class="w-20 h-20 mx-auto bg-white rounded-2xl border-2 border-gray-900 flex items-center justify-center text-3xl font-black text-brand-red shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
            <?php echo e(substr(Auth::user()->first_name ?? Auth::user()->name, 0, 1)); ?>

        </div>
    </div>
</section>


<section class="bg-slate-50 min-h-screen pb-24 pt-12">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="flex flex-col lg:flex-row gap-8">
            
            
            <aside class="lg:w-1/4 w-full">
                <div class="bg-white p-4 rounded-[2rem] sticky top-28 border-2 border-gray-900 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
                    <div class="grid grid-cols-2 lg:grid-cols-1 gap-3">
                        <button onclick="switchTab('profile')" id="btn-tab-profile" class="tab-btn group flex items-center justify-center lg:justify-start gap-3 px-6 py-4 rounded-xl transition-all font-black uppercase tracking-widest text-[10px] border-2 border-transparent">
                            <i class="fas fa-user-circle text-lg"></i> <span>Profil</span>
                        </button>
                        <button onclick="switchTab('address')" id="btn-tab-address" class="tab-btn group flex items-center justify-center lg:justify-start gap-3 px-6 py-4 rounded-xl transition-all font-black uppercase tracking-widest text-[10px] border-2 border-transparent">
                            <i class="fas fa-map-marker-alt text-lg"></i> <span>Alamat</span>
                        </button>
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="lg:mt-4 col-span-2 lg:col-span-1">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full flex items-center justify-center lg:justify-start gap-3 px-6 py-4 rounded-xl transition-all font-black uppercase tracking-widest text-[10px] text-red-500 hover:bg-red-50 border-2 border-transparent hover:border-red-100">
                                <i class="fas fa-sign-out-alt text-lg"></i> <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            
            <main class="lg:w-3/4 w-full">
                
                
                <div id="section-profile" class="tab-content animate-fade-in">
                    <div class="bg-white rounded-[2.5rem] p-8 lg:p-10 border-2 border-gray-900 shadow-sm">
                        <form method="POST" action="<?php echo e(route('user.profile.update')); ?>">
                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8 border-b-2 border-gray-100 pb-6">
                                <h2 class="text-2xl font-black text-gray-900 italic uppercase tracking-tighter">Edit <span class="text-brand-red">Profil</span></h2>
                                <button type="submit" class="w-full sm:w-auto bg-gray-900 text-white font-black py-3 px-6 rounded-xl text-[10px] uppercase tracking-widest hover:bg-brand-red transition-all shadow-[4px_4px_0px_0px_rgba(220,38,38,1)] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[1px] hover:translate-y-[1px] border-2 border-transparent">
                                    Simpan Perubahan
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div class="group">
                                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-1 mb-2 block">Nama Depan</label>
                                    <input type="text" name="first_name" value="<?php echo e(old('first_name', Auth::user()->first_name)); ?>" 
                                           class="w-full bg-white border-2 border-gray-200 p-4 rounded-xl focus:border-gray-900 outline-none font-bold text-sm transition-all" placeholder="Nama Panggilan" required>
                                </div>
                                
                                <div class="group">
                                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-1 mb-2 block">Nama Belakang</label>
                                    <input type="text" name="last_name" value="<?php echo e(old('last_name', Auth::user()->last_name)); ?>" 
                                           class="w-full bg-white border-2 border-gray-200 p-4 rounded-xl focus:border-gray-900 outline-none font-bold text-sm transition-all" placeholder="Nama Keluarga" required>
                                </div>
                                
                                <div class="group">
                                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-1 mb-2 block">Tanggal Lahir</label>
                                    <input type="date" name="birth_date" value="<?php echo e(old('birth_date', Auth::user()->birth_date)); ?>" 
                                           class="w-full bg-white border-2 border-gray-200 p-4 rounded-xl focus:border-gray-900 outline-none font-bold text-sm transition-all">
                                    <p class="text-[9px] text-gray-400 mt-2 ml-1 italic">* Promo ultah menantimu!</p>
                                </div>
                                
                                <div class="group">
                                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-1 mb-2 block">Nomor Telepon</label>
                                    <input type="text" name="phone_number" value="<?php echo e(old('phone_number', Auth::user()->phone_number)); ?>" 
                                           class="w-full bg-white border-2 border-gray-200 p-4 rounded-xl focus:border-gray-900 outline-none font-bold text-sm transition-all" required>
                                </div>
                                
                                <div class="group md:col-span-2">
                                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-1 mb-2 block">Email Address</label>
                                    <input type="email" value="<?php echo e(Auth::user()->email); ?>" 
                                           class="w-full bg-slate-50 border-2 border-transparent p-4 rounded-xl font-bold text-gray-400 text-sm cursor-not-allowed" readonly>
                                    <p class="text-[9px] text-gray-400 mt-2 ml-1 italic">* Email tidak dapat diubah demi keamanan.</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                
                <div id="section-address" class="tab-content hidden animate-fade-in">
                    <div class="bg-white rounded-[2.5rem] p-8 lg:p-10 border-2 border-gray-900 shadow-sm">
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-10 border-b-2 border-gray-100 pb-8">
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 italic uppercase tracking-tighter">Buku <span class="text-brand-red">Alamat</span></h2>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Kelola lokasi pengantaran pizza Anda</p>
                            </div>
                            <button onclick="openAddressModal()" class="w-full sm:w-auto bg-white text-gray-900 border-2 border-gray-900 font-black py-3 px-6 rounded-xl text-[10px] uppercase tracking-widest hover:bg-gray-900 hover:text-white transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px]">
                                + Tambah Alamat
                            </button>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <?php $__empty_1 = true; $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="relative group p-6 rounded-2xl border-2 transition-all duration-300 <?php echo e($address->is_primary ? 'border-brand-red bg-red-50/30' : 'border-gray-200 bg-white hover:border-gray-400'); ?>">
                                    <?php if($address->is_primary): ?>
                                        <div class="absolute -top-3 left-6 bg-brand-red text-white text-[8px] font-black uppercase px-3 py-1 rounded-md tracking-widest border-2 border-brand-red shadow-sm z-10">UTAMA</div>
                                    <?php endif; ?>
                                    <div class="flex flex-col md:flex-row justify-between gap-6">
                                        <div class="flex-grow">
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="px-2 py-1 bg-gray-900 text-white text-[9px] font-black uppercase rounded-md tracking-widest"><?php echo e($address->label); ?></span>
                                                
                                                <span class="text-xs font-bold text-gray-500"><?php echo e($address->receiver_name ?? $address->phone); ?></span>
                                            </div>
                                            
                                            
                                            <h4 class="text-sm font-black text-gray-900 uppercase leading-tight mb-1">
                                                <?php echo e($address->detail_address ?? Str::limit($address->address, 50)); ?>

                                            </h4>
                                            
                                            
                                            <?php if($address->map_address): ?>
                                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wide leading-relaxed mt-1">
                                                    <i class="fas fa-map-pin mr-1 text-brand-red"></i> <?php echo e(Str::limit($address->map_address, 80)); ?>

                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex flex-row md:flex-col gap-2 shrink-0">
                                            
                                            <button onclick="openAddressModal(<?php echo e(json_encode($address)); ?>)" class="bg-white border-2 border-gray-200 text-gray-600 w-10 h-10 rounded-xl flex items-center justify-center hover:border-gray-900 hover:text-gray-900 transition-all shadow-sm"><i class="fas fa-pen text-xs"></i></button>
                                            <?php if(!$address->is_primary): ?>
                                                <form action="<?php echo e(route('user.address.delete', $address)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="button" class="btn-delete bg-white border-2 border-gray-200 text-red-500 w-10 h-10 rounded-xl flex items-center justify-center hover:border-red-500 hover:bg-red-50 transition-all shadow-sm"><i class="fas fa-trash text-xs"></i></button>
                                                </form>
                                                <form action="<?php echo e(route('user.address.set-primary', $address->id)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="bg-white border-2 border-gray-200 text-gray-400 w-10 h-10 rounded-xl flex items-center justify-center hover:border-brand-red hover:text-brand-red transition-all shadow-sm group/star" title="Jadikan Utama"><i class="fas fa-star text-xs group-hover/star:fas"></i></button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="py-16 text-center border-2 border-dashed border-gray-200 rounded-[2rem]">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 border-2 border-gray-100"><i class="fas fa-map-signs text-2xl"></i></div>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Belum ada alamat tersimpan.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</section>


<div id="addressModal" class="fixed inset-0 z-[100] hidden bg-gray-900/80 backdrop-blur-sm p-4 flex items-center justify-center">
    <div class="bg-white w-full max-w-4xl rounded-[2.5rem] border-2 border-gray-900 shadow-2xl overflow-hidden animate-pop-in max-h-[90vh] flex flex-col">
        <div class="p-6 border-b-2 border-gray-100 flex justify-between items-center bg-white sticky top-0 z-20">
            <h3 id="modalTitle" class="text-xl font-black uppercase italic text-gray-900 tracking-tighter">Form Alamat</h3>
            <button onclick="closeAddressModal()" class="w-10 h-10 rounded-xl bg-gray-100 text-gray-500 flex items-center justify-center hover:bg-brand-red hover:text-white transition-all"><i class="fas fa-times"></i></button>
        </div>
        
        <form id="addressForm" action="<?php echo e(route('user.address.store')); ?>" method="POST" class="overflow-y-auto custom-scrollbar p-6 lg:p-8">
            <?php echo csrf_field(); ?> <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                
                <div class="space-y-5">
                    
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-1 mb-2 block">Nama Penerima</label>
                        <input type="text" name="receiver_name" id="in-receiver" placeholder="Nama orang yang menerima" class="w-full bg-white border-2 border-gray-200 p-4 rounded-xl focus:border-gray-900 outline-none font-bold text-sm transition-all" required>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-1 mb-2 block">Label</label>
                            <input type="text" name="label" id="in-label" placeholder="Rumah/Kantor" class="w-full bg-white border-2 border-gray-200 p-4 rounded-xl focus:border-gray-900 outline-none font-bold text-sm transition-all" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-1 mb-2 block">No. HP</label>
                            <input type="tel" name="phone" id="in-phone" class="w-full bg-white border-2 border-gray-200 p-4 rounded-xl focus:border-gray-900 outline-none font-bold text-sm transition-all" required>
                        </div>
                    </div>

                    
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-1 mb-2 block">Alamat Peta (Otomatis)</label>
                        <textarea name="map_address" id="full-address" rows="2" class="w-full bg-slate-50 border-2 border-gray-200 p-4 rounded-xl font-bold text-xs text-gray-500 italic cursor-not-allowed" readonly></textarea>
                        <p class="text-[9px] text-brand-red mt-1 italic font-bold">* Digunakan untuk titik GPS kurir</p>
                    </div>

                    
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-1 mb-2 block">Detail Alamat</label>
                        <textarea name="detail_address" id="in-detail" rows="2" placeholder="Contoh: Jl. Pahlawan, No.1, Unit Melati, Lobby Utara, ..." class="w-full bg-white border-2 border-gray-200 p-4 rounded-xl focus:border-gray-900 outline-none font-bold text-sm transition-all" required></textarea>
                        <p class="text-[9px] text-gray-400 mt-1 italic font-bold">* Bantu kurir mengenali rumahmu</p>
                    </div>

                    <input type="hidden" name="latitude" id="lat"><input type="hidden" name="longitude" id="lng">
                    <input type="hidden" name="city" id="city"><input type="hidden" name="province" id="province">
                </div>

                
                <div class="flex flex-col h-full">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-1 mb-2 block">Cari Titik</label>
                        <div class="flex gap-2 mb-2">
                            <input type="text" id="map-search" class="w-full bg-white border-2 border-gray-200 p-3 rounded-xl focus:border-gray-900 outline-none font-bold text-xs transition-all" placeholder="Ketik nama jalan...">
                            <button type="button" onclick="searchAddress()" class="bg-gray-900 text-white px-4 rounded-xl font-black text-[9px] uppercase shadow-sm">Cari</button>
                        </div>
                    </div>
                    
                    <div class="map-wrapper flex-grow relative">
                        <div id="map"></div>
                        <button type="button" onclick="getCurrentLocation(event)" id="btn-gps" class="absolute top-2 right-2 z-[999] bg-white text-brand-red p-2 rounded-lg border-2 border-gray-200 shadow-md hover:bg-brand-red hover:text-white transition-all text-xs font-bold">
                            <i class="fas fa-location-arrow"></i> GPS
                        </button>
                    </div>
                    
                    <p class="text-[9px] font-bold text-gray-400 uppercase italic mt-3 text-center tracking-tight"><i class="fas fa-info-circle mr-1"></i> Geser pin merah ke titik yang tepat</p>
                    
                    <div class="mt-auto pt-6">
                        <button type="submit" id="submitBtn" class="w-full bg-brand-red text-white font-black py-4 rounded-xl uppercase tracking-widest text-xs shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all border-2 border-transparent">
                            Simpan Alamat
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    function switchTab(tab) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.getElementById('section-' + tab).classList.remove('hidden');
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.className = "tab-btn group flex items-center justify-center lg:justify-start gap-3 px-6 py-4 rounded-xl transition-all font-black uppercase tracking-widest text-[10px] border-2 border-transparent text-gray-400 hover:bg-gray-50";
        });
        const activeBtn = document.getElementById('btn-tab-' + tab);
        activeBtn.className = "tab-btn group flex items-center justify-center lg:justify-start gap-3 px-6 py-4 rounded-xl transition-all font-black uppercase tracking-widest text-[10px] border-2 border-gray-900 bg-gray-900 text-white shadow-[4px_4px_0px_0px_rgba(220,38,38,1)]";
        sessionStorage.setItem('active_profile_tab', tab);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const activeTab = sessionStorage.getItem('active_profile_tab') || 'profile';
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('add_address')) {
            switchTab('address'); // Pindah ke tab alamat dulu
            openAddressModal();   // Langsung buka modal tambah
            // Bersihkan URL tanpa refresh agar tidak terbuka lagi saat di-reload
            window.history.replaceState({}, document.title, window.location.pathname + "#address");
        } else {
            switchTab(activeTab);
        }
    });

    let map = null;
    let marker = null;

    function openAddressModal(data = null) {
        document.getElementById('addressModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        const form = document.getElementById('addressForm');
        const submitBtn = document.getElementById('submitBtn');
        const title = document.getElementById('modalTitle');
        const methodInput = document.getElementById('formMethod');

        if (data) {
            title.innerText = "Edit Alamat";
            let updateRoute = "<?php echo e(route('user.address.update', ':id')); ?>";
            form.action = updateRoute.replace(':id', data.id);
            methodInput.value = "PUT";
            submitBtn.innerText = "Simpan Perubahan";
            
            // Populate Fields
            document.getElementById('in-receiver').value = data.receiver_name || "<?php echo e(Auth::user()->name); ?>";
            document.getElementById('in-label').value = data.label;
            document.getElementById('in-phone').value = data.phone;
            document.getElementById('in-detail').value = data.detail_address || ""; 
            document.getElementById('full-address').value = data.map_address || data.address || ""; 
            
            document.getElementById('lat').value = data.latitude;
            document.getElementById('lng').value = data.longitude;
            document.getElementById('city').value = data.city || "";
            document.getElementById('province').value = data.province || "";
        } else {
            title.innerText = "Tambah Alamat Baru";
            form.action = "<?php echo e(route('user.address.store')); ?>";
            methodInput.value = "POST";
            submitBtn.innerText = "Simpan Alamat";
            form.reset();
            // Default receiver name ke nama user
            document.getElementById('in-receiver').value = "<?php echo e(Auth::user()->name); ?>";
        }
        
        setTimeout(() => { initMap(data); if(map) map.invalidateSize(); }, 300); 
    }

    function initMap(data) {
        if (!map) {
            map = L.map('map', { zoomControl: false }).setView([-6.200000, 106.816666], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
            L.control.zoom({ position: 'bottomright' }).addTo(map);
            marker = L.marker([-6.200000, 106.816666], { draggable: true }).addTo(map);
            
            const updateLoc = (lat, lng) => {
                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;
                reverseGeocode(lat, lng);
            };
            marker.on('dragend', (e) => { const pos = e.target.getLatLng(); updateLoc(pos.lat, pos.lng); });
            map.on('click', (e) => { marker.setLatLng(e.latlng); updateLoc(e.latlng.lat, e.latlng.lng); });
        }
        
        if (data && data.latitude) {
            const pos = [data.latitude, data.longitude];
            map.setView(pos, 16); marker.setLatLng(pos);
        } else {
            const defaultPos = [-6.200000, 106.816666]; 
            map.setView(defaultPos, 13); marker.setLatLng(defaultPos);
        }
    }

    function closeAddressModal() { 
        document.getElementById('addressModal').classList.add('hidden'); 
        document.body.style.overflow = 'auto'; 
    }

    async function searchAddress() {
        const q = document.getElementById('map-search').value;
        if(!q) return;
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${q}`);
            const data = await res.json();
            if(data.length > 0) {
                const pos = [parseFloat(data[0].lat), parseFloat(data[0].lon)];
                map.setView(pos, 16); marker.setLatLng(pos);
                document.getElementById('lat').value = pos[0];
                document.getElementById('lng').value = pos[1];
                document.getElementById('full-address').value = data[0].display_name; // Set Map Address
            }
        } catch (e) { console.error(e); }
    }

    async function reverseGeocode(lat, lng) {
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
            const data = await res.json();
            if(data.address) {
                document.getElementById('full-address').value = data.display_name; // Set Map Address Only
                document.getElementById('city').value = data.address.city || data.address.town || data.address.municipality || data.address.suburb || '';
                document.getElementById('province').value = data.address.state || '';
            }
        } catch (e) { console.error(e); }
    }

    function getCurrentLocation(event) {
        const btn = event.currentTarget;
        const oldHTML = btn.innerHTML;
        if(!navigator.geolocation) return Swal.fire('Error', 'GPS tidak didukung', 'error');
        btn.innerHTML = '<i class="fas fa-spinner animate-spin"></i>';
        btn.disabled = true;
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const lat = pos.coords.latitude; const lng = pos.coords.longitude;
                map.setView([lat, lng], 18); marker.setLatLng([lat, lng]);
                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;
                reverseGeocode(lat, lng);
                btn.innerHTML = oldHTML; btn.disabled = false;
            },
            () => { Swal.fire('Error', 'Gagal ambil lokasi.', 'error'); btn.innerHTML = oldHTML; btn.disabled = false; },
            { enableHighAccuracy: true }
        );
    }

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            Swal.fire({
                title: 'Hapus Alamat?', text: "Yakin ingin menghapus alamat ini?", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#DC2626', cancelButtonColor: '#9CA3AF', confirmButtonText: 'Ya, Hapus!'
            }).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\pizza-boxx-web-v2\resources\views/pages/customer/profile.blade.php ENDPATH**/ ?>