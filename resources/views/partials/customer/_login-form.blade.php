<form method="POST" action="{{ route('login') }}" class="space-y-2">
    @csrf
    <div>
        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
            </span>
            <input id="email" name="email" type="email" autocomplete="email" required
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                   placeholder="anda@email.com" value="{{ old('email') }}">
        </div>
    </div>
    <div>
        <div class="flex justify-between items-center mb-1">
            <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
            <a href="#" class="text-sm font-medium text-red-600 hover:text-red-500">Lupa password?</a>
        </div>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </span>
            <input id="password" name="password" type="password" autocomplete="current-password" required
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                   placeholder="••••••••">
        </div>
    </div>
    <div class="flex items-center">
        <input id="remember_me" name="remember" type="checkbox"
               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
        <label for="remember_me" class="ml-2 block text-sm text-gray-900">Ingat saya</label>
    </div>
    <div>
        <button type="submit"
                class="w-full flex justify-center py-2 px-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
            Login
        </button>
    </div>
</form>