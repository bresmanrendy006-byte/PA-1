<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{

    protected $fillable = [
        'user_id',
        'menu_id',
        'quantity',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'user_id' => 'integer',
        'menu_id' => 'integer'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    // Cart milik user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cart memiliki menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR
    |--------------------------------------------------------------------------
    */

    // subtotal
    public function getSubtotalAttribute()
    {
        if(!$this->menu){
            return 0;
        }

        return $this->quantity * $this->menu->price;
    }

    // subtotal rupiah
    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal,0,',','.');
    }

    // harga rupiah
    public function getFormattedPriceAttribute()
    {
        if(!$this->menu){
            return 'Rp 0';
        }

        return 'Rp ' . number_format($this->menu->price,0,',','.');
    }

}