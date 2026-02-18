<?php
    $socials = [
        ['icon' => 'fab fa-instagram', 'link' => config('services.social.instagram')],
        ['icon' => 'fab fa-tiktok', 'link' => config('services.social.tiktok')],
    ];
?>

<footer class="pb-10 pt-10 bg-white relative overflow-hidden">
    
    <div class="absolute inset-0 bg-pizza-pattern opacity-[0.03] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 lg:px-12 relative z-10">
        
        
        <div class="bg-gray-900 border-2 border-gray-800 rounded-[3rem] p-10 lg:p-16 shadow-[12px_12px_0px_0px_rgba(0,0,0,0.2)] relative">
            
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-14 mb-16">
                
                
                <div class="flex flex-col items-center lg:items-start justify-start">
                    <div class="group">
                        <img src="<?php echo e(asset('images/pizza-boxx-logo.png')); ?>" 
                             alt="Pizza Boxx"
                             class="h-16 w-16 drop-shadow-2xl transition-transform duration-500 group-hover:rotate-12 group-hover:scale-110">
                    </div>
                    
                    
                    <div class="flex gap-3 mt-10">
                        <?php $__currentLoopData = $socials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $soc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($soc['link']); ?>" 
                               target="_blank"
                               class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-brand-red hover:border-brand-red transition-all group">
                                <i class="<?php echo e($soc['icon']); ?> text-lg transition-transform group-hover:scale-110"></i>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <div>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.35em] text-white/30 mb-8 italic flex items-center gap-2">
                        <span class="w-2 h-2 bg-brand-red rounded-full"></span> Navigasi
                    </h3>
                    <ul class="space-y-4">
                        <?php $__currentLoopData = [
                            ['route' => 'home', 'label' => 'Beranda'],
                            ['route' => 'menu.index', 'label' => 'Menu'],
                            ['route' => 'about', 'label' => 'Tentang Kami'],
                            ['route' => 'contact', 'label' => 'Kontak'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="<?php echo e(route($link['route'])); ?>"
                               class="text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-white transition-all flex items-center gap-2 group">
                                <span class="h-0.5 bg-brand-red transition-all duration-300 w-0 group-hover:w-4"></span>
                                <?php echo e($link['label']); ?>

                            </a>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>

                
                <div>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.35em] text-white/30 mb-8 italic flex items-center gap-2">
                        <span class="w-2 h-2 bg-brand-red rounded-full"></span> Legal
                    </h3>
                    <ul class="space-y-4">
                        <?php $__currentLoopData = ['Privacy Policy','Terms of Service','Refund Policy']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $legal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="#" class="text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-white transition-all flex items-center gap-2 group">
                                <span class="h-0.5 bg-brand-red transition-all duration-300 w-0 group-hover:w-4"></span>
                                <?php echo e($legal); ?>

                            </a>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>

                
                <div class="space-y-6">
                    <h3 class="text-[11px] font-black uppercase tracking-[0.35em] text-white/30 mb-2 italic flex items-center gap-2">
                        <span class="w-2 h-2 bg-brand-red rounded-full"></span> Kontak
                    </h3>
                    <div class="bg-white/5 p-6 rounded-[2rem] border border-white/10 space-y-4">
                        <div class="flex gap-4 items-start">
                            <i class="fas fa-map-marker-alt text-brand-red mt-1"></i>
                            <p class="text-sm font-medium text-gray-300 leading-relaxed">Jl. Pizza Raya No. 123, Depok, Jawa Barat</p>
                        </div>
                        <div class="flex gap-4 items-center">
                            <i class="fas fa-phone text-brand-red"></i>
                            <p class="text-sm font-bold text-gray-300">+62 812 3456 7890</p>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="pt-10 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-white/20">
                    <span class="text-brand-red font-black">© <?php echo e(date('Y')); ?> PIZZA BOXX</span>. ALL RIGHTS RESERVED.
                </p>
                <div class="flex items-center gap-6 text-2xl text-white/10">
                    <i class="fab fa-cc-visa hover:text-white transition-colors"></i>
                    <i class="fab fa-cc-mastercard hover:text-white transition-colors"></i>
                    <i class="fab fa-cc-paypal hover:text-white transition-colors"></i>
                </div>
            </div>
        </div>
    </div>
</footer><?php /**PATH C:\xampp\htdocs\pizza-boxx-web-v2\resources\views/partials/customer/footer.blade.php ENDPATH**/ ?>