<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_code',
        'user_id',
        'location_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'order_type',
        'payment_method',
        'is_offline',
        'status',
        'delivery_address',
        'delivery_notes',
        'latitude',
        'longitude',
        'qr_code_path',
        'pickup_pin',
        'is_qr_verified',
        'subtotal_amount',
        'discount_amount',
        'delivery_fee',
        'total_amount',
        'promo_id',
        'delivery_employee_id',
        'delivered_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'delivered_at' => 'datetime',
        'is_offline' => 'boolean',
        'is_qr_verified' => 'boolean',
    ];

    /**
     * Relationship: An order can belong to a user (customer).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: An order belongs to a location (store).
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Relationship: An order can use one promo.
     */
    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class);
    }

    /**
     * Relationship: An order can be handled by a delivery employee.
     */
    public function deliveryEmployee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_employee_id');
    }

    /**
     * Relationship: An order has many order items.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    
    /**
     * Aksesor untuk mendapatkan status yang ramah pengguna.
     * Mengubah status teknis dari database menjadi string yang mudah dibaca pelanggan.
     */
    public function getUserFriendlyStatusAttribute(): string
    {
        return match ($this->status) {
            'pending'           => 'Menunggu Konfirmasi',
            'accepted'          => 'Pesanan Diterima',
            'preparing'         => 'Sedang Dimasak',
            'ready_for_delivery'=> 'Siap Diantar',
            'ready_for_pickup'  => 'Siap Diambil',
            'on_delivery'       => 'Kurir Menuju Lokasimu',
            'delivered'         => 'Pesanan Tiba',
            'completed'         => 'Selesai',
            'cancelled'         => 'Dibatalkan',
            default             => 'Status Tidak Diketahui',
        };
    }
}