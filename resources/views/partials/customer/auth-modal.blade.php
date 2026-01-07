<div x-data="{ open: false, isLogin: true }"
     x-cloak
     @open-auth-modal.window="open = true; isLogin = $event.detail.form === 'login'"
     x-show="open"
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true">

    <div class="flex items-center justify-center min-h-screen p-4 text-center">

        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>

        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all max-w-md w-full">

            <button @click="open = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/pizza-boxx-logo.png') }}" alt="Pizza Boxx Logo" class="w-16 h-16 mb-2">
                    <h3 class="text-xl font-bold text-gray-900" id="modal-title">
                        <span x-show="isLogin">Masuk ke Pizza Boxx</span>
                        <span x-show="!isLogin">Daftar Akun Baru</span>
                    </h3>
                </div>
            </div>

            @if ($errors->any())
                <div x-init="open = true; isLogin = '{{ old('email') && !$errors->has('name') ? 'true' : 'false' }}'; console.log('errors')"
                     class="mx-6 mt-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    <p class="font-bold">Oops! Terjadi kesalahan.</p>
                    <ul class="list-disc ml-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div x-show="isLogin" class="p-6">
                @include('partials.customer._login-form')
                <p class="mt-4 text-center text-sm text-gray-600">
                    Belum punya akun? <a href="#" @click.prevent="isLogin = false" class="font-semibold text-red-600 hover:text-red-500">Daftar sekarang</a>
                </p>
            </div>

            <div x-show="!isLogin" class="p-6">
                @include('partials.customer._register-form')
                <p class="mt-4 text-center text-sm text-gray-600">
                    Sudah punya akun? <a href="#" @click.prevent="isLogin = true" class="font-semibold text-red-600 hover:text-red-500">Masuk di sini</a>
                </p>
            </div>

        </div>
    </div>
</div>