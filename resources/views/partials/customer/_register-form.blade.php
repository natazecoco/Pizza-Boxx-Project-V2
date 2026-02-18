<form method="POST" action="{{ route('register') }}" 
    x-data="{ loading: false }" 
    @submit="loading = true" 
    class="space-y-4">
    @csrf

    {{-- Nama --}}
    <div class="group">
        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2 ml-1 group-focus-within:text-brand-red transition-colors">Nama Lengkap</label>
        <input type="text" name="name" required value="{{ old('name') }}"
               class="w-full px-5 py-4 bg-white border-2 {{ $errors->has('name') ? 'border-brand-red' : 'border-gray-200' }} rounded-xl focus:border-gray-900 focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)] outline-none font-bold text-gray-900 transition-all shadow-sm placeholder-gray-300 text-sm"
               placeholder="Contoh: Budi Santoso">
        @error('name')
            <p class="text-[9px] font-black uppercase text-brand-red mt-2 ml-2 italic flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i> {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Email --}}
    <div class="group">
        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2 ml-1 group-focus-within:text-brand-red transition-colors">Email Address</label>
        <input type="email" name="email" required value="{{ old('email') }}"
               class="w-full px-5 py-4 bg-white border-2 {{ $errors->has('email') ? 'border-brand-red' : 'border-gray-200' }} rounded-xl focus:border-gray-900 focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)] outline-none font-bold text-gray-900 transition-all shadow-sm placeholder-gray-300 text-sm"
               placeholder="nama@email.com">
        @error('email')
            <p class="text-[9px] font-black uppercase text-brand-red mt-2 ml-2 italic flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i> {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Password Grid --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="group">
            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2 ml-1 group-focus-within:text-brand-red transition-colors">Password</label>
            <input type="password" name="password" required
                   class="w-full px-5 py-4 bg-white border-2 {{ $errors->has('password') ? 'border-brand-red' : 'border-gray-200' }} rounded-xl focus:border-gray-900 focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)] outline-none font-bold text-gray-900 transition-all shadow-sm placeholder-gray-300 text-sm"
                   placeholder="••••••••">
        </div>
        <div class="group">
            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2 ml-1 group-focus-within:text-brand-red transition-colors">Konfirmasi</label>
            <input type="password" name="password_confirmation" required
                   class="w-full px-5 py-4 bg-white border-2 border-gray-200 rounded-xl focus:border-gray-900 focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)] outline-none font-bold text-gray-900 transition-all shadow-sm placeholder-gray-300 text-sm"
                   placeholder="••••••••">
        </div>
        @error('password')
            <div class="col-span-2 text-[9px] font-black uppercase text-brand-red ml-2 italic flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i> {{ $message }}
            </div>
        @enderror
    </div>

    {{-- Submit Button --}}
    <button type="submit" 
            :disabled="loading"
            class="w-full bg-brand-red hover:bg-gray-900 text-white font-black py-4 rounded-xl uppercase tracking-[0.2em] text-xs shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[2px] hover:translate-y-[2px] transition-all flex items-center justify-center gap-3 disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none border-2 border-transparent mt-4">
        
        <template x-if="loading">
            <div class="flex items-center gap-2">
                <i class="fas fa-circle-notch animate-spin"></i>
                <span>MEMBUAT AKUN...</span>
            </div>
        </template>

        <template x-if="!loading">
            <div class="flex items-center gap-2">
                <span>DAFTAR SEKARANG</span>
                <i class="fas fa-user-plus"></i>
            </div>
        </template>
    </button>
</form>