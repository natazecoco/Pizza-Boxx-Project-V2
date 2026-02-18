@php
    $statusColors = [
        'pending' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
        'accepted' => 'bg-blue-50 text-blue-600 border-blue-100',
        'preparing' => 'bg-pizza-red/5 text-pizza-red border-pizza-red/10',
        'ready_for_delivery' => 'bg-purple-50 text-purple-600 border-purple-100',
        'ready_for_pickup' => 'bg-purple-50 text-purple-600 border-purple-100',
        'on_delivery' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
        'delivered' => 'bg-green-50 text-green-600 border-green-100',
        'completed' => 'bg-green-100 text-green-700 border-green-200',
        'cancelled' => 'bg-red-50 text-brand-red border-red-100',
    ];
    $cStatus = strtolower($status);
    $colorClass = $statusColors[$cStatus] ?? 'bg-gray-50 text-gray-600 border-gray-100';
@endphp

<span class="px-3 py-1 rounded-lg border {{ $colorClass }} text-[9px] font-black uppercase tracking-widest inline-flex items-center gap-1 shadow-sm">
    <span class="w-1.5 h-1.5 rounded-full bg-current {{ in_array($cStatus, ['preparing', 'on_delivery']) ? 'animate-pulse' : '' }}"></span>
    {{ str_replace('_', ' ', $status) }}
</span>