<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image'
    ];

    /**
     * Relasi ke Cart
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Relasi ke OrderItem
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi ke Stock (1 menu punya 1 stok)
     */
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    /**
     * Format harga ke Rupiah
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}