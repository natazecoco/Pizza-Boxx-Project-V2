<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Portal Pegawai - Pizza Boxx</title>
    <link rel="icon" href="<?php echo e(asset('images/pizza-boxx-logo.png')); ?>" type="image/x-icon">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/apple-touch-icon.png')); ?>">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="preload" href="<?php echo e(asset('images/pizza-boxx-logo.png')); ?>" as="image">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.5s ease-out forwards;
        }
        /* Pindahkan animasi dari dashboard/order-detail ke sini */
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.9s cubic-bezier(.4,0,.2,1) both; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-800 flex" x-data="{ sidebarOpen: false }">
    
    <div class="fixed top-0 left-0 right-0 h-16 bg-white shadow-sm flex items-center px-4 z-40 md:hidden">
        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none p-2">
            <i class="fas fa-bars text-2xl"></i>
        </button>
        <span class="ml-4 font-black text-brand-red">PIZZA BOXX</span>
    </div>

    <?php echo $__env->make('partials.employee.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false" 
         x-cloak 
         x-transition:enter="transition opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-40 md:hidden"
         style="display: none;">
    </div>

    <div class="flex-grow min-h-screen md:ml-64 pt-16 md:pt-0">
        <main class="py-12 px-6">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>


    <?php echo $__env->yieldPushContent('scripts'); ?>

    <script>
        
        const PizzaAlert = Swal.mixin({
            customClass: {
                confirmButton: 'bg-gray-900 text-white font-black uppercase italic px-8 py-4 rounded-2xl mx-2 hover:bg-brand-red transition-all text-[10px] tracking-[0.2em] shadow-xl',
                cancelButton: 'bg-slate-200 text-gray-400 font-black uppercase italic px-8 py-4 rounded-2xl mx-2 hover:bg-gray-300 transition-all text-[10px] tracking-[0.2em]',
                popup: 'rounded-[3rem] border-none shadow-2xl p-8',
                title: 'font-black uppercase italic text-gray-900 tracking-tighter text-2xl mb-2',
                htmlContainer: 'font-bold text-gray-500 text-[11px] uppercase tracking-widest'
            },
            buttonsStyling: false,
            showClass: { popup: 'animate-fade-in-up' }
        });

        
        <?php if(session('success')): ?>
            PizzaAlert.fire({
                icon: 'success',
                title: 'BERHASIL!',
                text: "<?php echo e(session('success')); ?>",
                timer: 2500,
                showConfirmButton: false
            });
        <?php endif; ?>

        <?php if(session('error')): ?>
            PizzaAlert.fire({
                icon: 'error',
                title: 'WADUH!',
                text: "<?php echo e(session('error')); ?>",
            });
        <?php endif; ?>
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\pizza-boxx-web-v2\resources\views/layouts/employee.blade.php ENDPATH**/ ?>