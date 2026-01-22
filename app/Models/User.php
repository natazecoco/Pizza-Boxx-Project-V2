<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <--- TAMBAHKAN INI
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    // Memberitahu Spatie bahwa Model ini bisa punya role dari berbagai pintu
    public function guardName(): array
    {
        return ['web', 'employee'];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'location_id', // <--- TAMBAHKAN INI
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // =========================================================
    // 1. HELPER ROLE (Cara B)
    // =========================================================

    /**
     * Cek apakah user adalah Super Admin (Pusat)
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Cek apakah user adalah Admin Cabang (Manager)
     */
    public function isBranchManager(): bool
    {
        return $this->role === 'branch_manager';
    }

    // =========================================================
    // 2. AKSES FILAMENT
    // =========================================================

    /**
     * Filament User Access Control
     * Only users with 'admin' or 'employee' role can access Filament panels.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isSuperAdmin() || $this->isBranchManager();
    }

    public function canManageOrders(): bool
    {
        return $this->isSuperAdmin() || $this->isBranchManager();
    }

    // =========================================================
    // 3. RELASI
    // =========================================================

    /**
     * Relationship: A user (employee) belongs to one location.
     */
    public function location(): BelongsTo // <--- TAMBAHKAN FUNGSI INI
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Relationship: An admin/employee can have many orders as a delivery employee.
     */
    public function ordersAsDeliveryEmployee(): HasMany
    {
        return $this->hasMany(Order::class, 'delivery_employee_id');
    }

    /**
     * Relationship: A user (customer) can have many orders.
     */
    public function ordersAsCustomer(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * Relasi ke alamat-alamat user
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

}