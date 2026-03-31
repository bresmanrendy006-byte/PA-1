<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image'
    ];

    /**
     * Relasi ke Cart
     * Menu bisa dimasukkan ke banyak cart
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Format harga ke Rupiah
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}
}