<?php $__env->startSection('content'); ?>

    
    <section class="relative min-h-[650px] flex items-center bg-brand-red overflow-hidden pt-28 md:pt-32 lg:pt-12">
        
        <div class="absolute inset-0 bg-pizza-pattern opacity-10"></div>
        
        <div class="container mx-auto px-6 lg:px-12 relative z-30 py-12 lg:py-0">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                
                <div class="order-first lg:order-last animate-fade-in">
                    <div class="relative group">
                        <img src="<?php echo e(asset('images/pizzabanner.png')); ?>" class="w-full rounded-[2.5rem] lg:rounded-[4rem] shadow-2xl border-4 border-white/10 rotate-2 lg:rotate-3 transition-transform duration-700">
                        <div class="absolute -bottom-4 -left-4 lg:-bottom-6 lg:-left-6 bg-brand-kraft p-4 lg:p-6 rounded-2xl lg:rounded-3xl shadow-xl animate-bounce">
                            <p class="text-brand-red font-black text-xl lg:text-2xl leading-none">100%<br><span class="text-[10px] lg:text-sm uppercase justify-center tracking-widest text-brand-red font-black">Fresh</span></p>
                        </div>
                    </div>
                </div>

                
                <div class="text-center lg:text-left">
                    <div class="inline-block mb-6 px-4 py-1 bg-white/10 backdrop-blur-md border border-white/20 rounded-full">
                        
                        <span class="text-base font-black text-brand-kraft uppercase tracking-widest">Premium Quality</span>
                    </div>
                    
                    <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white italic tracking-tighter leading-[0.9] mb-8">
                        PIZZA<br><span class="text-brand-kraft drop-shadow-xl">BOXX</span>
                    </h1>
                    <p class="text-base md:text-xl lg:text-2xl font-medium text-white/90 mb-10 leading-relaxed">
                        Good Pizza, <span class="bg-brand-kraft text-brand-red px-2 rounded-lg italic">Great Pizza.</span>
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="<?php echo e(route('menu.index')); ?>" class="w-full sm:w-auto bg-white text-brand-red font-bold py-4 px-10 rounded-2xl shadow-xl hover:scale-105 transition-all uppercase text-sm tracking-widest text-center">
                            Pesan Sekarang
                        </a>
                        <a href="#kenapa-kami" class="py-4 px-6 text-white font-bold text-sm uppercase tracking-widest hover:text-brand-kraft transition-colors">
                            Kenapa Kami? <i class="fas fa-arrow-down ml-2 animate-bounce"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <div class="bg-gray-900 py-4 lg:py-6 overflow-hidden border-y-4 border-brand-kraft relative z-20">
        <div class="flex whitespace-nowrap animate-marquee">
            <?php for($i = 0; $i < 10; $i++): ?>
                <div class="flex items-center gap-4 mx-8">
                    <span class="text-white font-black italic text-xl lg:text-3xl uppercase tracking-tighter">Pizza <span class="text-brand-red">Boxx</span></span>
                    <i class="fas fa-star text-brand-kraft text-xs"></i>
                    <span class="text-gray-400 font-black italic text-xl lg:text-3xl uppercase tracking-tighter">Keju Melimpah</span>
                    <i class="fas fa-star text-brand-kraft text-xs"></i>
                    <span class="text-white font-black italic text-xl lg:text-3xl uppercase tracking-tighter">Best Takeaway</span>
                    <i class="fas fa-star text-brand-kraft text-xs"></i>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    
    <section class="py-24 bg-white relative">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-16 gap-6">
                <div class="text-left">
                    <span class="bg-brand-red text-white px-3 py-1 rounded-md font-black text-[10px] uppercase tracking-widest mb-3 inline-block">Trending Now</span>
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 italic uppercase tracking-tighter leading-none">
                        Paling <span class="text-brand-red underline decoration-brand-kraft decoration-8 underline-offset-4">Dicari</span>
                    </h2>
                </div>
                <a href="<?php echo e(route('menu.index')); ?>" class="group flex items-center gap-2 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-brand-red transition-colors">
                    Lihat Semua Menu 
                    <div class="w-6 h-6 rounded-full border-2 border-gray-200 flex items-center justify-center group-hover:border-brand-red group-hover:bg-brand-red group-hover:text-white transition-all">
                        <i class="fas fa-arrow-right text-[8px]"></i>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $__empty_1 = true; $__currentLoopData = $bestSellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="group relative bg-slate-50 rounded-[2.5rem] p-6 border-2 border-transparent hover:border-gray-900 hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 transition-all duration-300">
                        
                        <div class="relative h-56 mb-6 overflow-hidden rounded-[1.5rem] bg-white border-2 border-gray-100 group-hover:border-transparent transition-all">
                            <img src="<?php echo e(asset('storage/' . $product->image_path)); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute top-3 right-3 bg-brand-red text-white text-[9px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest shadow-md">
                                Best Seller
                            </div>
                        </div>

                        
                        <h3 class="text-xl font-black text-gray-900 uppercase italic mb-2 leading-none">
                            <?php echo e($product->name); ?>

                        </h3>
                        <p class="text-gray-500 text-xs font-bold leading-relaxed mb-6 line-clamp-2 h-10">
                            <?php echo e(Str::limit($product->description, 80)); ?>

                        </p>

                        <div class="flex items-end justify-between border-t-2 border-dashed border-gray-200 pt-4">
                            <div>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Mulai Dari</p>
                                <span class="text-brand-red font-black text-xl italic">
                                    Rp <?php echo e(number_format($product->base_price, 0, ',', '.')); ?>

                                </span>
                            </div>
                            <a href="<?php echo e(route('menu.show', $product->id)); ?>" class="w-10 h-10 bg-gray-900 text-white rounded-xl flex items-center justify-center hover:bg-brand-red transition-colors shadow-lg">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full py-20 text-center bg-slate-50 rounded-[3rem] border-2 border-dashed border-gray-200">
                        <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Belum ada menu populer saat ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    
    <section id="kenapa-kami" class="scroll-mt-[50px] lg:scroll-mt-[50px] py-24 bg-slate-50 rounded-[3rem] lg:rounded-[5rem] -mt-10 relative z-20">
        <div class="container mx-auto px-4 lg:px-12">
            <div class="max-w-4xl mb-16 text-center mx-auto">
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 italic uppercase leading-[0.8] tracking-tighter">
                    Kenapa Harus <br class="hidden lg:block">
                    <span class="text-brand-red underline decoration-brand-kraft decoration-[12px] lg:decoration-[20px] underline-offset-4 uppercase">Pizza Boxx?</span>
                </h2>
                <p class="text-gray-500 font-medium mt-6 text-sm md:text-lg lg:text-xl leading-relaxed max-w-2xl mx-auto">
                    Kami mengemas kebahagiaan dalam setiap kotak dengan standar kualitas tinggi yang tidak akan kamu temukan di tempat lain.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 lg:gap-6 auto-rows-[160px] md:auto-rows-[240px]">
                
                
                <div class="col-span-2 row-span-2 bg-brand-red rounded-[2.5rem] lg:rounded-[3rem] p-6 lg:p-12 text-white relative overflow-hidden group shadow-xl">
                    <div class="relative z-10 h-full flex flex-col justify-end">
                        <i class="fas fa-bolt text-3xl lg:text-5xl mb-4 lg:mb-6 text-brand-kraft"></i>
                        <h3 class="text-2xl lg:text-5xl font-black uppercase italic leading-none mb-3">Kilat<br>Sampai Pintu</h3>
                        <p class="text-red-50 text-xs md:text-sm lg:text-base font-medium leading-relaxed opacity-90">
                            Garansi pizza tetap panas dengan sistem pengantaran khusus yang super cepat langsung ke depan pintu Anda.
                        </p>
                    </div>
                    <div class="absolute inset-0 bg-pizza-pattern opacity-10 group-hover:scale-110 transition-transform"></div>
                    <div class="absolute top-0 right-0 p-6 lg:p-8 text-white/20">
                        <i class="fas fa-shipping-fast text-4xl lg:text-6xl"></i>
                    </div>
                </div>

                
                <div class="col-span-2 bg-brand-kraft rounded-[2.5rem] lg:rounded-[3.5rem] p-6 lg:p-10 flex items-center justify-between group relative overflow-hidden shadow-lg">
                    <div class="relative z-10 text-left">
                        <h3 class="text-lg lg:text-3xl font-black text-brand-red uppercase italic leading-none">Bahan Baku<br>Premium</h3>
                        <p class="text-gray-800 text-[10px] md:text-xs lg:text-sm font-semibold mt-2 lg:mt-3 leading-relaxed">
                            Dipilih dengan cermat untuk rasa autentik di setiap gigitan pizza.
                        </p>
                    </div>
                    <div class="relative z-10 w-12 h-12 lg:w-20 lg:h-20 bg-white rounded-2xl lg:rounded-[2rem] shadow-md flex items-center justify-center">
                        <i class="fas fa-cheese text-brand-red text-xl lg:text-3xl"></i>
                    </div>
                </div>

                
                <div class="col-span-1 bg-gray-900 rounded-[2.5rem] lg:rounded-[3rem] p-4 lg:p-8 flex flex-col items-center justify-center text-center group border-b-4 lg:border-b-8 border-brand-yellow/20 shadow-lg relative overflow-hidden">
                    
                    
                    <div class="w-14 h-14 lg:w-20 lg:h-20 bg-white rounded-full flex items-center justify-center mb-4 lg:mb-6 p-2 lg:p-3">
                        <img src="<?php echo e(asset('images/logo_halal.png')); ?>"
                             alt="Sertifikasi Halal Resmi"
                             class="w-full h-full object-contain">
                    </div>
                    <span class="text-white text-sm lg:text-2xl font-black uppercase tracking-tighter leading-none">
                        100% <span class="text-purple-900">Halal</span>
                    </span>
                    <p class="text-gray-400 text-[10px] lg:text-xs mt-2 font-medium">Sertifikasi MUI Resmi</p>
                </div>

                
                <div class="col-span-1 bg-white border border-gray-100 rounded-[2.5rem] lg:rounded-[3rem] p-4 lg:p-8 flex flex-col items-center justify-center text-center group shadow-md">
                    <span class="text-3xl lg:text-5xl font-black text-gray-900 leading-none">4.9</span>
                    <div class="flex text-brand-red text-[10px] lg:text-sm my-2 lg:my-3">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <span class="text-gray-700 text-[10px] lg:text-xs font-semibold">Ulasan Pelanggan</span>
                </div>
            </div>
        </div>
    </section>

    
    <section class="py-24 bg-white overflow-hidden">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="text-center mb-16">
                
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 uppercase italic">
                    Apa Kata <span class="text-brand-red">Mereka?</span>
                </h2>
            </div>
            
            <div class="flex flex-col lg:flex-row gap-8">
                <div class="flex-1 bg-slate-50 p-8 rounded-[2.5rem] italic text-gray-600 relative">
                    <span class="text-6xl text-brand-kraft absolute top-4 left-4 opacity-50">“</span>
                    <p class="relative z-10 text-sm md:text-base leading-relaxed mb-6">"Pizza paling worth it se-Surabaya. Keju mozarellanya nggak pelit dan adonannya renyah tapi tetap lembut di dalam. Boxnya juga keren!"</p>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-brand-red rounded-full"></div>
                        <div>
                            <p class="text-gray-900 font-black uppercase text-xs">Rina Anastasia</p>
                            <p class="text-gray-400 text-[10px]">Pecinta Keju</p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 bg-brand-red p-8 rounded-[2.5rem] italic text-white relative">
                    <span class="text-6xl text-white/20 absolute top-4 left-4">“</span>
                    <p class="relative z-10 text-sm md:text-base leading-relaxed mb-6">"Gak nyangka pengirimannya secepat itu. Masih panas banget pas sampai. Anak-anak langsung lahap makannya. Langganan terus pokoknya!"</p>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white rounded-full"></div>
                        <div>
                            <p class="text-white font-black uppercase text-xs">Budi Santoso</p>
                            <p class="text-white/60 text-[10px]">Ayah 2 Anak</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <section class="py-24 bg-slate-50 relative overflow-hidden border-t border-gray-100 rounded-t-[3rem] lg:rounded-t-[5rem]">
        <div class="absolute inset-0 bg-pizza-pattern opacity-[0.03]"></div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 mb-8 uppercase italic tracking-tighter leading-none">
                Sudah Siap <br class="lg:hidden"> Mencoba?
            </h2>
            <p class="text-gray-500 mb-12 text-sm md:text-lg lg:text-xl font-medium max-w-xl mx-auto leading-relaxed">
                Jangan tunggu sampai dingin. Pesan sekarang dan nikmati kehangatan pizza terbaik.
            </p>
            
            <a href="<?php echo e(route('menu.index')); ?>" class="inline-flex items-center justify-center gap-3 bg-brand-red hover:bg-gray-900 text-white font-black py-5 px-12 rounded-2xl text-sm uppercase tracking-[0.2em] shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all whitespace-nowrap border-2 border-transparent">
                LIHAT MENU <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .animate-marquee {
        animation: marquee 30s linear infinite;
        display: flex;
        width: max-content;
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\pizza-boxx-web-v2\resources\views/pages/Front/home.blade.php ENDPATH**/ ?>