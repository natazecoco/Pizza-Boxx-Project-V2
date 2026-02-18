

<?php $__env->startSection('content'); ?>
<div class="container mx-auto py-10 px-2 md:px-0" 
     x-data="{ 
        filter: 'all', 
        search: '', 
        showPinModal: false, 
        activeOrder: { id: '', name: '' },
        pin: '' 
     }">

    
    <div class="mb-8">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Daftar Pesanan 🍕</h2>
        <p class="text-gray-500 mt-1">Pantau dan kelola proses pembuatan pizza pelanggan secara real-time.</p>
    </div>

    
    <div class="mb-8 flex flex-wrap items-center gap-3">
        <button @click="filter = 'all'" 
                :class="filter === 'all' ? 'bg-brand-red text-white shadow-lg scale-105' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200'"
                class="px-6 py-2 rounded-full border text-sm font-bold transition-all duration-200 flex items-center gap-2">
            Semua <span class="bg-white/20 px-2 py-0.5 rounded-full text-[10px]"><?php echo e($orders->count()); ?></span>
        </button>
        
        <button @click="filter = 'delivery'" 
                :class="filter === 'delivery' ? 'bg-blue-600 text-white shadow-lg scale-105' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200'"
                class="px-6 py-2 rounded-full border text-sm font-bold transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-truck"></i> Delivery <span class="bg-white/20 px-2 py-0.5 rounded-full text-[10px]"><?php echo e($orders->where('order_type', 'delivery')->count()); ?></span>
        </button>
        
        <button @click="filter = 'pickup'" 
                :class="filter === 'pickup' ? 'bg-green-600 text-white shadow-lg scale-105' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200'"
                class="px-6 py-2 rounded-full border text-sm font-bold transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-store"></i> Pickup <span class="bg-white/20 px-2 py-0.5 rounded-full text-[10px]"><?php echo e($orders->where('order_type', 'pickup')->count()); ?></span>
        </button>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-red-50 p-6 rounded-lg shadow-md flex items-center justify-between border-l-4 border-red-500">
            <div>
                <h2 class="text-xl font-semibold text-red-700">Pending</h2>
                <p class="text-4xl font-bold text-brand-red mt-2"><?php echo e($orders->where('status', 'pending')->count()); ?></p>
            </div>
            <div class="p-3 bg-red-200 rounded-full"><i class="fas fa-clock text-brand-red text-2xl"></i></div>
        </div>
        <div class="bg-yellow-50 p-6 rounded-lg shadow-md flex items-center justify-between border-l-4 border-yellow-500">
            <div>
                <h2 class="text-xl font-semibold text-yellow-700">Proses</h2>
                <p class="text-4xl font-bold text-yellow-600 mt-2">
                    <?php echo e($orders->whereIn('status', ['accepted', 'preparing', 'ready_for_delivery', 'ready_for_pickup', 'on_delivery'])->count()); ?>

                </p>
            </div>
            <div class="p-3 bg-yellow-200 rounded-full"><i class="fas fa-spinner fa-spin text-yellow-600 text-2xl"></i></div>
        </div>
        <div class="bg-green-50 p-6 rounded-lg shadow-md flex items-center justify-between border-l-4 border-green-500">
            <div>
                <h2 class="text-xl font-semibold text-green-700">Selesai</h2>
                <p class="text-4xl font-bold text-green-600 mt-2"><?php echo e($orders->whereIn('status', ['completed', 'delivered'])->count()); ?></p>
            </div>
            <div class="p-3 bg-green-200 rounded-full"><i class="fas fa-check-double text-green-600 text-2xl"></i></div>
        </div>
    </div>
    
    
    <div class="mt-8" id="orders-container">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            
            
            <div class="bg-white p-4 rounded-lg shadow-md border-t-4 border-red-500">
                <h3 class="text-lg font-bold text-red-700 mb-4 uppercase tracking-wider">Pending</h3>
                <div class="space-y-4">
                    <?php $__currentLoopData = $orders->where('status', 'pending'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div x-show="filter === 'all' || filter === '<?php echo e($order->order_type); ?>'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95">
                            <?php echo $__env->make('partials.employee.order-card', ['order' => $order], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="bg-white p-4 rounded-lg shadow-md border-t-4 border-yellow-500">
                <h3 class="text-lg font-bold text-yellow-700 mb-4 uppercase tracking-wider">Dalam Proses</h3>
                <div class="space-y-4">
                    <?php $__currentLoopData = $orders->whereIn('status', ['accepted', 'preparing', 'ready_for_delivery', 'ready_for_pickup', 'on_delivery']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div x-show="filter === 'all' || filter === '<?php echo e($order->order_type); ?>'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95">
                            <?php echo $__env->make('partials.employee.order-card', ['order' => $order], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="bg-white p-4 rounded-lg shadow-md border-t-4 border-green-500">
                <h3 class="text-lg font-bold text-green-700 mb-4 uppercase tracking-wider">Selesai (Hari Ini)</h3>
                <div class="space-y-4">
                    <?php $__currentLoopData = $orders->whereIn('status', ['completed', 'delivered'])->sortByDesc('updated_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div x-show="filter === 'all' || filter === '<?php echo e($order->order_type); ?>'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95">
                            <?php echo $__env->make('partials.employee.order-card', ['order' => $order], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    
    <div x-show="showPinModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm px-4 no-print"
         style="display: none;" x-cloak>
        
        <div class="bg-white rounded-3xl w-full max-w-sm shadow-2xl overflow-hidden transform transition-all"
             @click.away="showPinModal = false">
            
            <div class="bg-gray-800 p-6 text-white text-center">
                <p class="text-xs font-bold uppercase tracking-widest text-red-400">Konfirmasi Pengambilan</p>
                <h3 class="text-xl font-black italic">#<span x-text="activeOrder.id"></span> - <span x-text="activeOrder.name"></span></h3>
            </div>

            <div class="p-8">
                <div class="mb-6">
                    <div class="bg-gray-100 rounded-2xl p-4 border-2 border-gray-200 text-center">
                        <div class="text-4xl font-black tracking-[0.3em] text-brand-red h-10" x-text="pin"></div>
                        <p class="text-[10px] text-gray-400 mt-2 uppercase font-bold">Masukkan 6-Digit PIN Pelanggan</p>
                    </div>
                </div>

                
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <template x-for="n in [1,2,3,4,5,6,7,8,9]">
                        <button @click="if(pin.length < 6) pin += n" class="h-14 bg-gray-50 hover:bg-red-50 rounded-xl font-black text-xl text-gray-700 transition-colors border border-gray-100">
                            <span x-text="n"></span>
                        </button>
                    </template>
                    <button @click="pin = ''" class="h-14 bg-red-100 text-brand-red rounded-xl font-bold flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>
                    <button @click="if(pin.length < 6) pin += '0'" class="h-14 bg-gray-50 rounded-xl font-black text-xl text-gray-700 border border-gray-100">0</button>
                    <button @click="submitPin(activeOrder.id, pin)" 
                            :disabled="pin.length < 6" 
                            class="h-14 bg-green-600 text-white rounded-xl font-bold flex items-center justify-center disabled:opacity-50">
                        <i class="fas fa-check"></i>
                    </button>
                </div>

                <button @click="showPinModal = false" class="w-full text-gray-400 font-bold text-sm hover:text-gray-600 transition-colors">
                    BATAL
                </button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<audio id="orderNotification" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" preload="auto"></audio>

<script>
    // --- 0. STATE GLOBAL ---
    let lastPendingCount = <?php echo e($orders->where('status', 'pending')->count()); ?>;
    let globalTimerInterval = null;

    // --- 1. FUNGSI UPDATE SEMUA TIMER (SLA) ---
    function updateAllTimers() {
        const timers = document.querySelectorAll('.sla-timer-badge');
        
        timers.forEach(badge => {
            const status = badge.dataset.status;
            if (['completed', 'delivered'].includes(status)) return;

            const startTime = new Date(badge.dataset.start);
            const sla = parseInt(badge.dataset.sla);
            const display = badge.querySelector('.timer-text');
            
            if (!display) return;

            const diff = Math.floor((Date.now() - startTime) / 1000);

            let h = Math.floor(diff / 3600);
            let m = Math.floor((diff % 3600) / 60);
            let s = diff % 60;
            display.innerText = (h > 0 ? h + 'j ' : '') + (m > 0 ? m + 'm ' : '') + s + 'd';

            if (diff >= sla) {
                if (!badge.classList.contains('bg-brand-red')) {
                    badge.className = "sla-timer-badge px-2 py-0.5 rounded font-bold uppercase tracking-wider flex items-center gap-1 bg-brand-red text-white animate-pulse transition-all duration-500";
                }
            } else if (diff >= (sla * 0.7)) {
                if (!badge.classList.contains('bg-yellow-500')) {
                    badge.className = "sla-timer-badge px-2 py-0.5 rounded font-bold uppercase tracking-wider flex items-center gap-1 bg-yellow-500 text-white transition-all duration-500";
                }
            }
        });
    }

    // --- 2. FUNGSI MEMULAI MESIN TIMER ---
    function startGlobalTimers() {
        if (globalTimerInterval) return;
        updateAllTimers();
        globalTimerInterval = setInterval(updateAllTimers, 1000);
    }

    // --- 3. FUNGSI REFRESH PESANAN (Polling AJAX) ---
    function refreshOrders() {
        fetch(window.location.href, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContainer = doc.querySelector('#orders-container');
            
            if (!newContainer) return;

            document.querySelector('#orders-container').innerHTML = newContainer.innerHTML;
            updateAllTimers();

            // LOGIKA AUDIO: Cek jika ada pesanan baru
            const currentPendingCount = doc.querySelectorAll('.border-red-500 .bg-white').length;
            if (currentPendingCount > lastPendingCount) {
                const alertSound = document.getElementById('orderNotification');
                alertSound.play().catch(e => console.log("Izin suara diperlukan"));
            }
            lastPendingCount = currentPendingCount;
        })
        .catch(err => console.warn('Polling Error:', err));
    }

    // --- 4. FUNGSI VERIFIKASI PIN ---
    function submitPin(orderId, pinCode) {
        if (pinCode.length < 6) return;
        
        PizzaAlert.fire({ 
            title: 'MEMVERIFIKASI...', 
            allowOutsideClick: false, 
            didOpen: () => { PizzaAlert.showLoading() } 
        });

        fetch("<?php echo e(route('pegawai.qr.verify')); ?>", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ order_id: orderId, pin: pinCode })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                PizzaAlert.fire({ 
                    icon: 'success', 
                    title: 'BERHASIL!', 
                    timer: 1500, 
                    showConfirmButton: false 
                });
                refreshOrders(); 
            } else {
                PizzaAlert.fire({ 
                    icon: 'error', 
                    title: 'PIN SALAH!', 
                    text: data.message 
                });
            }
        })
        .catch(error => {
            PizzaAlert.fire({ 
                icon: 'error', 
                title: 'KONEKSI ERROR', 
                text: 'Gagal menghubungi server.' 
            });
        });
    }

    // --- EKSEKUSI ---
    document.addEventListener('DOMContentLoaded', () => {
        startGlobalTimers();
        setInterval(refreshOrders, 15000); // Polling tiap 15 detik
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.employee', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\pizza-boxx-web-v2\resources\views/pages/employee/orders.blade.php ENDPATH**/ ?>