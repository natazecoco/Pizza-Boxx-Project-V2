<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', 
        'receiver_name',   
        'label', 
        'phone',
        'address',         
        'map_address',     
        'detail_address',  
        'city', 
        'province', 
        'latitude', 
        'longitude',
        'is_primary'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_primary' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Relationship: An address belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}