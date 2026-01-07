@extends('layouts.employee')

@section('content')
<div class="container mx-auto max-w-lg py-12 px-4">
    <div class="mb-6">
        <a href="{{ route('pegawai.dashboard') }}" class="inline-flex items-center text-red-600 hover:text-red-800 font-semibold transition-colors duration-200">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Kembali ke Dashboard
        </a>
    </div>
    <div class="bg-white p-8 rounded-2xl shadow-xl animate-fade-in-up">
        <h2 class="text-2xl font-bold mb-4 text-red-700 text-center">Verifikasi PIN Pesanan</h2>
        <p class="text-gray-600 text-center mb-6">Masukkan PIN yang diberikan pelanggan untuk menyelesaikan pesanan.</p>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-4 text-sm animate-fade-in">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-4 text-sm animate-fade-in">
                <p>{{ session('error') }}</p>
            </div>
        @endif
        
        <form method="POST" action="{{ route('pegawai.qr.verify') }}" class="flex flex-col gap-4">
            @csrf
            <label for="pin" class="block font-semibold mb-1">PIN Pesanan</label>
            <input type="text" name="pin" id="pin"
                   class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400"
                   placeholder="e.g. 123456" required autofocus>
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors mt-2">Verifikasi</button>
        </form>
    </div>
</div>
@endsection