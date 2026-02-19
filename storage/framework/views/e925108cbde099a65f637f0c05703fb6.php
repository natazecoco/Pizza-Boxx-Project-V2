<?php $__env->startSection('title', 'Verifikasi Pengambilan #' . $order->id); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto max-w-lg py-12 px-4">
    
    
    <div class="mb-6">
        <a href="<?php echo e(route('pegawai.dashboard')); ?>" class="inline-flex items-center text-xs font-black uppercase tracking-widest text-gray-400 hover:text-brand-red transition-all group">
            <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
            Kembali ke Dashboard
        </a>
    </div>

    
    <div class="bg-gray-900 rounded-t-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden border-b border-white/10">
        
        <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
        
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-brand-kraft mb-1">Verifikasi Ambil</p>
                    <h2 class="text-2xl font-black italic uppercase tracking-tighter">#<?php echo e($order->id); ?> - <?php echo e($order->customer_name); ?></h2>
                </div>
                <div class="bg-brand-red p-3 rounded-2xl shadow-lg shadow-brand-red/20">
                    <i class="fas fa-receipt text-xl"></i>
                </div>
            </div>
            
            <div class="flex gap-4 text-[10px] font-black uppercase tracking-widest text-white/50">
                <span class="flex items-center gap-1.5"><i class="fas fa-pizza-slice text-brand-kraft"></i> <?php echo e($order->orderItems->sum('quantity')); ?> Items</span>
                <span class="flex items-center gap-1.5"><i class="fas fa-wallet text-brand-kraft"></i> Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?></span>
            </div>
        </div>
    </div>

    
    <div class="bg-white p-8 lg:p-10 rounded-b-[2.5rem] shadow-2xl shadow-slate-200">
        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-red-50 text-brand-red rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                <i class="fas fa-shield-alt text-3xl"></i>
            </div>
            <h3 class="text-xl font-black text-gray-900 uppercase italic tracking-tighter">Masukkan PIN Keamanan</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-2 px-6">Minta pelanggan menunjukkan 6-digit PIN dari halaman lacak pesanan mereka.</p>
        </div>

        <form id="verifyForm" class="space-y-8">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">
            
            <div class="relative group">
                <label for="pin" class="block text-[10px] font-black uppercase text-gray-400 mb-3 tracking-[0.2em] text-center">6-Digit PIN Pelanggan</label>
                <div class="relative">
                    <input type="text" name="pin" id="pin"
                           class="w-full bg-slate-50 border-2 border-gray-100 rounded-[2rem] px-4 py-5 text-center text-4xl font-black tracking-[0.5em] text-brand-red focus:border-brand-red focus:bg-white focus:ring-0 transition-all placeholder:text-gray-100 outline-none"
                           placeholder="000000" maxlength="6" inputmode="numeric" required autofocus autocomplete="off">
                </div>
            </div>

            <button type="submit" id="btnVerify" 
                    class="w-full bg-gray-900 text-white font-black py-5 rounded-[2rem] shadow-xl hover:bg-brand-red transition-all transform active:scale-95 flex items-center justify-center gap-3 uppercase tracking-[0.2em] text-xs">
                <span>Konfirmasi Pengambilan</span>
                <i class="fas fa-check-circle"></i>
            </button>
        </form>

        
        <div class="mt-10 pt-8 border-t border-dashed border-gray-100">
            <div class="flex gap-4 items-start p-4 bg-blue-50/50 rounded-2xl">
                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                <p class="text-[10px] text-blue-700 font-bold uppercase tracking-wide leading-relaxed">
                    Pastikan piza sudah lengkap sebelum verifikasi. Setelah PIN dikonfirmasi, status pesanan akan otomatis menjadi <span class="text-blue-900">Selesai</span>.
                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.getElementById('verifyForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('btnVerify');
        const formData = new FormData(this);
        const originalContent = btn.innerHTML;

        // 1. Loading State pada Tombol
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> MEMPROSES...';

        // 2. Loading State di Tengah (Krusial untuk verifikasi)
        PizzaAlert.fire({ 
            title: 'VERIFIKASI PIN...', 
            allowOutsideClick: false, 
            didOpen: () => { PizzaAlert.showLoading() } 
        });

        fetch("<?php echo e(route('pegawai.qr.verify')); ?>", {
            method: "POST",
            body: formData,
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Berhasil: PizzaAlert sukses sebentar lalu balik ke dashboard
                PizzaAlert.fire({
                    icon: 'success',
                    title: 'PIN VALID!',
                    text: 'Pesanan resmi diambil. Terima kasih!',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href = "<?php echo e(route('pegawai.dashboard')); ?>";
                });
            } else {
                // Gagal: Tampilkan pesan error di tengah
                PizzaAlert.fire({
                    icon: 'error',
                    title: 'PIN SALAH!',
                    text: data.message || 'Silakan cek kembali PIN pelanggan.'
                });
                
                // Reset Tombol & Input
                btn.disabled = false;
                btn.innerHTML = originalContent;
                document.getElementById('pin').value = ''; 
                document.getElementById('pin').focus();
            }
        })
        .catch(error => {
            PizzaAlert.fire({ 
                icon: 'error', 
                title: 'ERROR SISTEM', 
                text: 'Terjadi masalah koneksi ke server.' 
            });
            btn.disabled = false;
            btn.innerHTML = originalContent;
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.employee', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\pizza-boxx-web-v2\resources\views/pages/employee/verify.blade.php ENDPATH**/ ?>