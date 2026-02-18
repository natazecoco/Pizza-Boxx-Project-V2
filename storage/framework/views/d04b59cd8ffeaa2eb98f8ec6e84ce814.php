<?php $__env->startSection('content'); ?>

<div class="container mx-auto px-4 py-6 max-w-lg" 
     x-data="{ 
        showPinModal: false, 
        pin: '', 
        orderId: '<?php echo e($order->id); ?>',
        customerName: '<?php echo e($order->customer_name); ?>'
     }">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="<?php echo e(route('pegawai.deliveries.index')); ?>" class="bg-white p-3 rounded-2xl shadow-sm text-gray-600 hover:text-brand-red transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-xl font-bold text-gray-800">Navigasi Pengantaran</h2>
    </div>

    
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 mb-6">
        <div class="bg-brand-red p-6 text-white">
            <p class="text-xs opacity-80 uppercase font-bold tracking-widest">Antar Pesanan #<?php echo e($order->id); ?></p>
            <h1 class="text-2xl font-black mt-1 uppercase"><?php echo e($order->customer_name); ?></h1>
        </div>
        
        <div class="p-6">
            <div class="flex gap-4 mb-8">
                <div class="bg-red-50 p-4 rounded-2xl text-brand-red h-fit">
                    <i class="fas fa-map-marked-alt text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                        Alamat Pengiriman:
                        
                        
                        <?php if($order->latitude && $order->longitude): ?>
                            <span class="bg-green-100 text-green-700 px-1.5 py-0.5 rounded text-[9px] font-black border border-green-200">
                                <i class="fas fa-crosshairs mr-1"></i> TITIK AKURAT
                            </span>
                        <?php endif; ?>
                    </p>
                    <p class="text-gray-700 font-bold leading-relaxed mt-1">
                        <?php echo e($order->delivery_address ?? 'Alamat belum diisi'); ?>

                    </p>
                    <?php if($order->delivery_notes): ?>
                        <div class="mt-2 bg-yellow-50 p-2 rounded-lg border border-yellow-100 text-xs text-orange-800 italic">
                            <span class="font-bold not-italic">Catatan:</span> "<?php echo e($order->delivery_notes); ?>"
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <?php
                // Cek apakah ada koordinat di database
                $hasCoords = $order->latitude && $order->longitude;
                
                if ($hasCoords) {
                    // JIKA ADA PIN POINT: Pakai Koordinat GPS (Akurasi 100%)
                    // Format: query=lat,lng
                    $mapsUrl = "https://www.google.com/maps/search/?api=1&query={$order->latitude},{$order->longitude}";
                    $mapsLabel = "BUKA TITIK LOKASI (GPS)";
                    $mapsIcon = "fa-map-pin"; // Icon Jarum
                    $btnColor = "bg-blue-600 hover:bg-blue-700 shadow-blue-200";
                } else {
                    // JIKA TIDAK ADA: Cari berdasarkan Teks Alamat (Akurasi Tebak-tebakan Google)
                    $mapsUrl = "https://www.google.com/maps/search/?api=1&query=" . urlencode($order->delivery_address);
                    $mapsLabel = "CARI ALAMAT DI MAPS";
                    $mapsIcon = "fa-search-location"; // Icon Kaca Pembesar
                    $btnColor = "bg-gray-600 hover:bg-gray-700 shadow-gray-200";
                }
            ?>

            
            <a href="<?php echo e($mapsUrl); ?>" 
               target="_blank"
               class="w-full <?php echo e($btnColor); ?> text-white py-4 rounded-2xl font-black text-center flex items-center justify-center gap-3 shadow-lg transition-all mb-4">
                <i class="fas <?php echo e($mapsIcon); ?>"></i> <?php echo e($mapsLabel); ?>

            </a>

            <?php
                $phone = $order->customer_phone;
                // Bersihkan nomor HP
                $phone = preg_replace('/[^0-9]/', '', $phone);
                if(str_starts_with($phone, '0')) {
                    $phone = '62' . substr($phone, 1);
                }
            ?>
            <a href="https://wa.me/<?php echo e($phone); ?>?text=Halo%20Kak%20<?php echo e(urlencode($order->customer_name)); ?>,%20kurir%20Pizza%20Boxx%20sedang%20menuju%20lokasi%20ya!" 
               target="_blank"
               class="w-full bg-green-500 text-white py-4 rounded-2xl font-black text-center flex items-center justify-center gap-3 shadow-lg shadow-green-100 hover:bg-green-600 transition-all">
                <i class="fab fa-whatsapp text-xl"></i> HUBUNGI PELANGGAN
            </a>
        </div>
    </div>

    
    <div class="bg-gray-50 rounded-3xl p-6 border border-gray-200 mb-24">
        <h3 class="text-xs font-bold text-gray-400 uppercase mb-4 tracking-widest">Cek Barang Bawaan:</h3>
        <ul class="space-y-3">
            <?php $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="flex justify-between items-center bg-white p-3 rounded-xl border border-gray-100">
                <span class="text-sm font-bold text-gray-700">
                    <span class="text-brand-red"><?php echo e($item->quantity); ?>x</span> <?php echo e($item->product_name); ?>

                    
                    
                    <?php if($item->options): ?>
                        <br>
                        <span class="text-[10px] text-gray-400 font-normal ml-5">
                             <?php
                                $opts = is_array($item->options) ? $item->options : explode(',', $item->options);
                            ?>
                            <?php $__currentLoopData = $opts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e(is_array($opt) ? ($opt['name'] ?? '-') : trim($opt)); ?><?php if(!$loop->last): ?>, <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </span>
                    <?php endif; ?>
                </span>
                <input type="checkbox" class="rounded text-brand-red focus:ring-red-500 w-5 h-5 border-gray-300 cursor-pointer">
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>

    
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/90 backdrop-blur-sm border-t border-gray-100 md:left-64 shadow-[0_-10px_20px_rgba(0,0,0,0.05)] z-50">
        <?php if($order->status == 'on_delivery'): ?>
            
            <button @click="showPinModal = true" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black shadow-xl hover:bg-indigo-700 transition-all uppercase tracking-tighter">
                <i class="fas fa-key mr-2"></i> Konfirmasi PIN & Selesai
            </button>
        <?php else: ?>
            <form action="<?php echo e(route('pegawai.orders.update-status', $order->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="status" value="on_delivery">
                <button type="submit" class="w-full bg-purple-600 text-white py-4 rounded-2xl font-black shadow-xl hover:bg-purple-700 transition-all uppercase tracking-tighter">
                    <i class="fas fa-motorcycle mr-2"></i> Konfirmasi: Saya Berangkat
                </button>
            </form>
        <?php endif; ?>
    </div>

    
    <div x-show="showPinModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm px-4"
         style="display: none;">
        
        <div class="bg-white rounded-3xl w-full max-w-sm shadow-2xl overflow-hidden transform transition-all"
             @click.away="showPinModal = false">
            
            <div class="bg-gray-800 p-6 text-white text-center">
                <p class="text-xs font-bold uppercase tracking-widest text-red-400">Verifikasi Kedatangan</p>
                <h3 class="text-xl font-black italic">#<?php echo e($order->id); ?> - <?php echo e($order->customer_name); ?></h3>
            </div>

            <div class="p-8">
                <div class="mb-6">
                    <div class="bg-gray-100 rounded-2xl p-4 border-2 border-gray-200 text-center">
                        <div class="text-4xl font-black tracking-[0.3em] text-brand-red h-10" x-text="pin"></div>
                        <p class="text-[10px] text-gray-400 mt-2 uppercase font-bold">Minta 6-Digit PIN dari Pelanggan</p>
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
                    <button @click="submitDeliveryPin('<?php echo e($order->id); ?>', pin)" 
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
<script>
    function submitDeliveryPin(orderId, pinCode) {
        if (pinCode.length < 6) return;
        
        // 1. Loading State di Tengah
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
                // Redirect jika sukses
                window.location.href = "<?php echo e(route('pegawai.deliveries.index')); ?>?success=delivered";
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
                title: 'ERROR SISTEM', 
                text: 'Gagal menghubungi server.' 
            });
        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.employee', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\pizza-boxx-web-v2\resources\views/pages/employee/delivery-detail.blade.php ENDPATH**/ ?>