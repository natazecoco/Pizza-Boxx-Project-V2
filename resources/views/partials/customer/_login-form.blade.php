<form method="POST" action="{{ route('login') }}"
    x-data="{ loading: false }" 
    @submit="loading = true" 
    class="space-y-5">
    @csrf

    {{-- Email --}}
    <div class="group">
        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2 ml-1 group-focus-within:text-brand-red transition-colors">Email Anda</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-gray-900 transition-colors">
                <i class="fas fa-envelope text-sm"></i>
            </span>
            <input type="email" name="email" required value="{{ old('email') }}"
                   class="w-full pl-12 pr-4 py-4 bg-white border-2 {{ $errors->has('email') ? 'border-brand-red' : 'border-gray-200' }} rounded-xl focus:border-gray-900 focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)] outline-none font-bold text-gray-900 transition-all placeholder-gray-300 text-sm"
                   placeholder="anda@email.com">
        </div>
        @error('email')
            <p class="text-[9px] font-black uppercase text-brand-red mt-2 ml-2 italic flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i> {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Password --}}
    <div class="group">
        <div class="flex justify-between items-center mb-2 ml-1">
            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 group-focus-within:text-brand-red transition-colors">Password</label>
            <!-- @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-[9px] font-black uppercase tracking-widest text-gray-400 hover:text-brand-red transition-colors">Lupa?</a>
            @endif -->
            <a href="javascript:void(0)" 
               onclick="Swal.fire({
                   icon: 'info',
                   title: 'Fitur Segera Hadir',
                   text: 'Silakan hubungi Admin untuk reset password manual.',
                   confirmButtonColor: '#DC2626'
               })" 
               class="text-[9px] font-black uppercase tracking-widest text-brand-red hover:underline italic transition-colors">
               Lupa?
            </a>
        </div>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-gray-900 transition-colors">
                <i class="fas fa-lock text-sm"></i>
            </span>
            <input type="password" name="password" required
                   class="w-full pl-12 pr-4 py-4 bg-white border-2 {{ $errors->has('password') ? 'border-brand-red' : 'border-gray-200' }} rounded-xl focus:border-gray-900 focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)] outline-none font-bold text-gray-900 transition-all placeholder-gray-300 text-sm"
                   placeholder="••••••••">
        </div>
        @error('password')
            <p class="text-[9px] font-black uppercase text-brand-red mt-2 ml-2 italic flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i> {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Remember Me --}}
    <div class="flex items-center ml-1">
        <input id="remember_me" name="remember" type="checkbox"
               class="w-5 h-5 text-brand-red border-2 border-gray-300 rounded focus:ring-brand-red focus:ring-offset-0 cursor-pointer">
        <label for="remember_me" class="ml-3 block text-[10px] font-black uppercase tracking-widest text-gray-500 cursor-pointer select-none">Ingat Saya</label>
    </div>

    {{-- Submit Button (Hard Shadow Style) --}}
    <button type="submit" 
            :disabled="loading"
            class="w-full bg-brand-red hover:bg-gray-900 text-white font-black py-4 rounded-xl uppercase tracking-[0.2em] text-xs shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[2px] hover:translate-y-[2px] transition-all flex items-center justify-center gap-3 disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none border-2 border-transparent">
        
        <template x-if="loading">
            <div class="flex items-center gap-2">
                <i class="fas fa-circle-notch animate-spin"></i>
                <span>MEMPROSES...</span>
            </div>
        </template>

        <template x-if="!loading">
            <div class="flex items-center gap-2">
                <span>MASUK SEKARANG</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </template>
    </button>
</form>