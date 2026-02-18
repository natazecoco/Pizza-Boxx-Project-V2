<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-slate-50 p-4 lg:p-8">
    
    <div class="w-full max-w-5xl mx-auto grid lg:grid-cols-2 bg-white rounded-[3rem] shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] overflow-hidden border-4 border-gray-900">

        
        <div class="p-8 md:p-16 flex flex-col justify-center bg-white relative order-2 lg:order-1">
            
            
            <a href="<?php echo e(route('home')); ?>" class="lg:hidden absolute top-6 right-6 text-gray-400 hover:text-brand-red">
                <i class="fas fa-times text-xl"></i>
            </a>

            
            <div class="mb-8 lg:hidden text-center">
                <img src="<?php echo e(asset('images/pizza-boxx-logo.png')); ?>" class="w-20 mx-auto">
            </div>

            
            <div class="mb-8 text-center lg:text-left">
                <h1 class="text-3xl lg:text-5xl font-black text-gray-900 italic uppercase tracking-tighter leading-none mb-2">
                    GABUNG SEKARANG
                </h1>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">
                    Daftar untuk nikmati promo eksklusif member
                </p>
            </div>

            
            <?php echo $__env->make('partials.customer._register-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <div class="mt-8 pt-8 border-t-2 border-dashed border-gray-100 text-center lg:text-left">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                    Sudah punya akun?
                    <a href="<?php echo e(route('login')); ?>" class="text-brand-red hover:text-gray-900 underline decoration-2 underline-offset-2 transition-colors ml-1">Login di sini →</a>
                </p>
            </div>
        </div>

        
        <div class="hidden lg:flex bg-brand-kraft p-12 xl:p-16 flex-col justify-between relative overflow-hidden text-gray-900 border-l-4 border-gray-900 order-1 lg:order-2">
            
            
            <div class="absolute inset-0 bg-pizza-pattern opacity-5"></div>
            
            
            <img src="<?php echo e(asset('images/pizzabanner.png')); ?>" class="absolute -left-24 top-1/2 -translate-y-1/2 w-[120%] opacity-20 -rotate-12 blur-sm pointer-events-none filter sepia">

            
            <div class="relative z-10 text-right">
                <a href="<?php echo e(route('home')); ?>" class="inline-block mb-10 group">
                    <div class="bg-white p-3 rounded-2xl border-2 border-gray-900 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] group-hover:translate-x-1 group-hover:translate-y-1 group-hover:shadow-none transition-all">
                        <img src="<?php echo e(asset('images/pizza-boxx-logo.png')); ?>" class="w-12 h-12 object-contain">
                    </div>
                </a>
                <h2 class="text-5xl font-black italic uppercase tracking-tighter leading-[0.9] mb-6">
                    BUKAN<br>SEKADAR<br><span class="text-brand-red text-6xl drop-shadow-sm">PIZZA.</span>
                </h2>
                <p class="text-gray-800 font-bold text-sm leading-relaxed max-w-xs ml-auto border-r-4 border-brand-red pr-4">
                    Bergabunglah dengan ribuan #PizzaLovers lainnya dan dapatkan kebahagiaan di setiap kotak.
                </p>
            </div>

            
            <div class="relative z-10 bg-white p-6 rounded-3xl border-2 border-gray-900 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] mt-12">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-brand-red flex items-center justify-center text-white font-black text-xl">
                        <i class="fas fa-heart text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] mb-1 text-gray-400">Customer Favorite</p>
                        <p class="text-sm font-bold italic text-gray-900">"Registrasi gampang, diskonnya nendang!"</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\pizza-boxx-web-v2\resources\views/auth/register.blade.php ENDPATH**/ ?>