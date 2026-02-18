<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Daftar Pengantaran 🚚</h2>
        <p class="text-gray-500 mt-1">Daftar pesanan yang harus segera diantar ke pelanggan.</p>
    </div>

    <?php if($deliveries->isEmpty()): ?>
        <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-dashed border-gray-300">
            <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-box-open text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Tidak ada pengantaran aktif</h3>
            <p class="text-gray-500">Semua pizza delivery sudah sampai ke tujuan atau belum ada pesanan baru.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $deliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden flex flex-col">
                    
                    <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-400">#<?php echo e($order->id); ?></span>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase 
                            <?php echo e($order->status == 'on_delivery' ? 'bg-purple-600 text-white animate-pulse' : 'bg-blue-100 text-blue-700'); ?>">
                            <?php echo e(str_replace('_', ' ', $order->status)); ?>

                        </span>
                    </div>

                    <div class="p-5 flex-grow">
                        <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo e($order->customer_name); ?></h3>
                        <p class="text-sm text-gray-600 line-clamp-2 mb-4">
                            <i class="fas fa-map-marker-alt text-red-500 mr-1"></i> <?php echo e($order->delivery_address); ?>

                        </p>
                        <p class="text-sm font-black text-gray-800">Rp<?php echo e(number_format($order->total_amount)); ?></p>
                    </div>

                    <div class="p-4 bg-white border-t">
                        <?php if($order->status == 'on_delivery'): ?>
                            
                            <a href="<?php echo e(route('pegawai.deliveries.show', $order->id)); ?>" 
                            class="flex items-center justify-center bg-indigo-600 text-white py-4 rounded-xl text-xs font-black hover:bg-indigo-700 transition-all gap-2 shadow-md uppercase tracking-wider">
                                <i class="fas fa-location-arrow"></i> Buka Navigasi & PIN
                            </a>
                        <?php else: ?>
                            
                            <form action="<?php echo e(route('pegawai.orders.update-status', $order->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="status" value="on_delivery">
                                <input type="hidden" name="redirect_type" value="to_detail"> 
                                
                                <button type="submit" class="w-full bg-purple-600 text-white py-4 rounded-xl text-xs font-black hover:bg-purple-700 transition-all flex items-center justify-center gap-2 shadow-md uppercase tracking-wider">
                                    <i class="fas fa-motorcycle"></i> Mulai Antar Sekarang
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.employee', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\pizza-boxx-web-v2\resources\views/pages/employee/deliveries.blade.php ENDPATH**/ ?>