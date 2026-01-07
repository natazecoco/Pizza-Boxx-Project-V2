@extends('layouts.employee')

@section('content')
<div class="container mx-auto py-10 px-2 md:px-0">
    <div class="mb-6">
        <a href="{{ route('pegawai.deliveries.index') }}" class="inline-flex items-center text-red-600 hover:text-red-800 font-semibold transition-colors duration-200">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Kembali ke Daftar Pengantaran
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-8 animate-fade-in-up">
        <h2 class="text-2xl font-bold mb-4 text-red-700">Detail Pengantaran #{{ $delivery->id }}</h2>
        
        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
                <p class="text-gray-700"><span class="font-semibold">ID Pesanan:</span> #{{ $delivery->order_id }}</p>
                <p class="text-gray-700"><span class="font-semibold">Nama Pelanggan:</span> {{ $delivery->order->customer_name ?? '-' }}</p>
                <p class="text-gray-700"><span class="font-semibold">Telepon:</span> {{ $delivery->order->customer_phone ?? '-' }}</p>
                <p class="text-gray-700"><span class="font-semibold">Alamat Pengantaran:</span> {{ $delivery->order->delivery_address ?? '-' }}</p>
                <p class="text-gray-700"><span class="font-semibold">Catatan:</span> {{ $delivery->order->delivery_notes ?? '-' }}</p>
            </div>
            <div class="space-y-2">
                <p class="text-gray-700"><span class="font-semibold">Status Pesanan:</span>
                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">{{ ucfirst($delivery->order->status ?? '-') }}</span>
                </p>
                <p class="text-gray-700"><span class="font-semibold">Total Pembayaran:</span>
                    <span class="text-lg font-bold text-red-600">Rp{{ number_format($delivery->order->total_amount ?? 0, 0, ',', '.') }}</span>
                </p>
                <p class="text-gray-700"><span class="font-semibold">Waktu Dibuat:</span> {{ $delivery->order->created_at->format('d M Y H:i') ?? '-' }}</p>
            </div>
        </div>

        <h3 class="text-lg font-bold mb-4 text-red-700">Formulir Update Pengantaran</h3>
        
        <form action="{{ route('pegawai.deliveries.update', $delivery->id) }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Kurir</label>
                    <select name="delivery_employee_id" class="w-full rounded border-gray-300 focus:ring-red-400 focus:border-red-400">
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @if($delivery->delivery_employee_id == $employee->id) selected @endif>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Status</label>
                    <select name="status" class="w-full rounded border-gray-300 focus:ring-red-400 focus:border-red-400">
                        <option value="pending" @if($delivery->status=='pending') selected @endif>Pending</option>
                        <option value="on_delivery" @if($delivery->status=='on_delivery') selected @endif>On Delivery</option>
                        <option value="delivered" @if($delivery->status=='delivered') selected @endif>Delivered</option>
                        <option value="failed" @if($delivery->status=='failed') selected @endif>Failed</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Ditugaskan</label>
                    <input type="datetime-local" name="assigned_at" value="{{ $delivery->assigned_at ? $delivery->assigned_at->format('Y-m-d\TH:i') : '' }}" class="w-full rounded border-gray-300 focus:ring-red-400 focus:border-red-400" />
                </div>
                <div>
                    <label class="block font-semibold mb-1">Diambil</label>
                    <input type="datetime-local" name="picked_up_at" value="{{ $delivery->picked_up_at ? $delivery->picked_up_at->format('Y-m-d\TH:i') : '' }}" class="w-full rounded border-gray-300 focus:ring-red-400 focus:border-red-400" />
                </div>
                <div>
                    <label class="block font-semibold mb-1">Sampai</label>
                    <input type="datetime-local" name="delivered_at" value="{{ $delivery->delivered_at ? $delivery->delivered_at->format('Y-m-d\TH:i') : '' }}" class="w-full rounded border-gray-300 focus:ring-red-400 focus:border-red-400" />
                </div>
                <div class="md:col-span-2">
                    <label class="block font-semibold mb-1">Catatan</label>
                    <textarea name="notes" class="w-full rounded border-gray-300 focus:ring-red-400 focus:border-red-400">{{ $delivery->notes }}</textarea>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow transition-all duration-200">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection