@extends('layouts.employee')

@section('content')
<div class="container mx-auto max-w-lg py-12 px-4">
    {{-- Navigasi Kembali --}}
    <div class="mb-6">
        <a href="{{ route('pegawai.dashboard') }}" class="inline-flex items-center text-gray-500 hover:text-red-600 font-bold transition-all duration-200 group">
            <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Dashboard
        </a>
    </div>

    {{-- Kartu Ringkasan Pesanan (Konteks buat Pegawai) --}}
    <div class="bg-gray-800 rounded-t-3xl p-6 text-white shadow-lg border-b border-gray-700">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-red-400">Verifikasi Ambil</p>
                <h2 class="text-2xl font-black italic">#{{ $order->id }} - {{ $order->customer_name }}</h2>
            </div>
            <div class="bg-red-600 p-2 rounded-lg">
                <i class="fas fa-box-open text-xl"></i>
            </div>
        </div>
        <div class="mt-4 flex gap-4 text-sm opacity-80">
            <span><i class="fas fa-pizza-slice mr-1"></i> {{ $order->orderItems->sum('quantity') }} Items</span>
            <span><i class="fas fa-wallet mr-1"></i> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Form Verifikasi --}}
    <div class="bg-white p-8 rounded-b-3xl shadow-2xl">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shield-alt text-red-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-extrabold text-gray-800">Masukkan PIN Keamanan</h3>
            <p class="text-gray-500 text-sm mt-1">Minta pelanggan menunjukkan PIN dari halaman detail pesanan mereka.</p>
        </div>

        <form id="verifyForm" class="space-y-6">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            
            <div class="relative group">
                <label for="pin" class="block text-xs font-black uppercase text-gray-400 mb-2 tracking-widest ml-1">6-Digit PIN</label>
                <div class="relative">
                    <input type="text" name="pin" id="pin"
                           class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-4 text-center text-3xl font-black tracking-[0.5em] text-red-600 focus:border-red-500 focus:bg-white focus:ring-0 transition-all placeholder:text-gray-200"
                           placeholder="000000" maxlength="6" inputmode="numeric" required autofocus>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none opacity-0 group-focus-within:opacity-100 transition-opacity">
                        <i class="fas fa-keyboard text-gray-300"></i>
                    </div>
                </div>
            </div>

            <button type="submit" id="btnVerify" class="w-full bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-red-200 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3">
                <span class="text-lg">KONFIRMASI PENGAMBILAN</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        {{-- Instruksi Tambahan --}}
        <div class="mt-8 pt-6 border-t border-gray-100">
            <div class="flex gap-3 items-start text-xs text-gray-400 leading-relaxed">
                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                <p>Pastikan item piza sudah lengkap dan sesuai pesanan sebelum melakukan verifikasi PIN. Setelah diverifikasi, status akan otomatis berubah menjadi <b>Selesai</b>.</p>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert2 & Script Logic --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('verifyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('btnVerify');
    const formData = new FormData(this);
    const originalContent = btn.innerHTML;

    // Loading State
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> MEMPROSES...';

    fetch("{{ route('pegawai.qr.verify') }}", {
        method: "POST",
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'PESANAN DIAMBIL!',
                text: 'Status pesanan berhasil diupdate menjadi Selesai.',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-3xl',
                    title: 'font-black text-green-600'
                }
            }).then(() => {
                window.location.href = "{{ route('pegawai.dashboard') }}";
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'PIN SALAH!',
                text: data.message,
                confirmButtonColor: '#dc2626',
                customClass: {
                    popup: 'rounded-3xl'
                }
            });
            btn.disabled = false;
            btn.innerHTML = originalContent;
            document.getElementById('pin').value = ''; // Reset input
            document.getElementById('pin').focus();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btn.disabled = false;
        btn.innerHTML = originalContent;
    });
});
</script>
@endsection