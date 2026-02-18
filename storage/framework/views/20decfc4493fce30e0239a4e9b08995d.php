
<div x-show="isModalOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95"
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/90 backdrop-blur-md"
     x-cloak
     style="display: none;">

    
    <div class="absolute inset-0" @click="isModalOpen = false"></div>

    
    <div class="relative w-full max-w-5xl bg-white rounded-[2.5rem] shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] border-4 border-gray-900 overflow-hidden flex flex-col lg:flex-row max-h-[90vh] transition-all duration-500"
         :class="isLogin ? 'lg:flex-row' : 'lg:flex-row-reverse'">
        
        
        <button @click="isModalOpen = false" class="absolute top-4 right-4 z-50 w-8 h-8 rounded-lg bg-white border-2 border-gray-900 text-gray-900 hover:bg-brand-red hover:text-white transition-all flex items-center justify-center shadow-md">
            <i class="fas fa-times text-sm"></i>
        </button>

        
        <div class="hidden lg:flex w-1/2 flex-col justify-between p-10 relative overflow-hidden transition-colors duration-500 border-gray-900"
             :class="isLogin ? 'bg-brand-red border-r-4 text-white' : 'bg-brand-kraft border-l-4 text-gray-900'">
            
            
            <div class="absolute inset-0 bg-pizza-pattern opacity-10"></div>
            
            
            <img src="<?php echo e(asset('images/pizzabanner.png')); ?>" 
                 class="absolute top-1/2 -translate-y-1/2 w-[140%] max-w-none opacity-20 blur-sm pointer-events-none transition-all duration-700"
                 :class="isLogin ? '-right-32 rotate-12' : '-left-32 -rotate-12 filter sepia'">

            
            <div class="relative z-10">
                
                <div class="bg-white p-3 rounded-2xl border-2 border-gray-900 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] w-fit mb-8 transition-transform hover:scale-105">
                    <img src="<?php echo e(asset('images/pizza-boxx-logo.png')); ?>" class="w-10 h-10 object-contain">
                </div>

                
                <div x-show="isLogin" x-transition:enter="transition ease-out duration-500 delay-100">
                    <h3 class="text-4xl font-black italic uppercase tracking-tighter leading-[0.9] mb-4 drop-shadow-md">
                        PIZZA<br>BOX<br><span class="text-brand-kraft text-5xl">SQUAD.</span>
                    </h3>
                    <p class="text-white/90 font-bold text-xs leading-relaxed max-w-xs border-l-4 border-brand-kraft pl-4">
                        Gabung member sekarang, dapatkan promo gila-gilaan tiap hari Jumat!
                    </p>
                </div>

                
                <div x-show="!isLogin" x-transition:enter="transition ease-out duration-500 delay-100">
                    <h3 class="text-4xl font-black italic uppercase tracking-tighter leading-[0.9] mb-4">
                        JOIN<br>THE<br><span class="text-brand-red text-5xl drop-shadow-sm">CLUB.</span>
                    </h3>
                    <p class="text-gray-800 font-bold text-xs leading-relaxed max-w-xs ml-auto text-right border-r-4 border-brand-red pr-4">
                        Bergabunglah dengan ribuan #PizzaLovers lainnya sekarang.
                    </p>
                </div>
            </div>

            
            <div class="relative z-10 mt-auto">
                
                <div x-show="isLogin" class="bg-gray-900 p-4 rounded-2xl border-2 border-white/20 shadow-lg mt-8">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-brand-kraft flex items-center justify-center text-brand-red font-black text-lg">“</div>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-[0.2em] mb-0.5 text-brand-kraft">Member Review</p>
                            <p class="text-xs font-bold italic text-white">"Login gampang, perut kenyang!"</p>
                        </div>
                    </div>
                </div>

                
                <div x-show="!isLogin" class="bg-white p-4 rounded-2xl border-2 border-gray-900 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] mt-8">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-brand-red flex items-center justify-center text-white font-black text-lg">
                            <i class="fas fa-heart text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-[0.2em] mb-0.5 text-gray-400">Customer Favorite</p>
                            <p class="text-xs font-bold italic text-gray-900">"Registrasi gampang, diskon nendang!"</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="w-full lg:w-1/2 bg-white flex flex-col overflow-y-auto custom-scrollbar p-8 lg:p-12 relative">
            
            
            <div class="lg:hidden text-center mb-6">
                 <img src="<?php echo e(asset('images/pizza-boxx-logo.png')); ?>" class="w-16 mx-auto">
            </div>

            <div class="mb-8 text-center lg:text-left shrink-0">
                <h1 class="text-3xl lg:text-4xl font-black text-gray-900 italic uppercase tracking-tighter leading-none mb-2">
                    <span x-show="isLogin">WELCOME BACK</span>
                    <span x-show="!isLogin">GABUNG YUK</span>
                </h1>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">
                    <span x-show="isLogin">Silakan masuk untuk mulai memesan</span>
                    <span x-show="!isLogin">Daftar untuk nikmati promo eksklusif</span>
                </p>
            </div>

            
            <div class="w-full shrink-0">
                
                <div x-show="isLogin" x-transition:enter="transition ease-out duration-300">
                    <?php echo $__env->make('partials.customer._login-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <div class="mt-8 pt-6 border-t-2 border-dashed border-gray-100 text-center lg:text-left">
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                            Belum punya akun? <a href="#" @click.prevent="isLogin = false" class="text-brand-red hover:text-gray-900 underline decoration-2 underline-offset-2 transition-colors ml-1">Daftar Sekarang →</a>
                        </p>
                    </div>
                </div>

                
                <div x-show="!isLogin" x-transition:enter="transition ease-out duration-300">
                    <?php echo $__env->make('partials.customer._register-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <div class="mt-8 pt-6 border-t-2 border-dashed border-gray-100 text-center lg:text-left">
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                            Sudah punya akun? <a href="#" @click.prevent="isLogin = true" class="text-brand-red hover:text-gray-900 underline decoration-2 underline-offset-2 transition-colors ml-1">← Login di sini</a>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\pizza-boxx-web-v2\resources\views/partials/customer/auth-modal.blade.php ENDPATH**/ ?>