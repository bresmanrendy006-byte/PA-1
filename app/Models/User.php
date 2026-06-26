<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke Cart
     * User memiliki banyak cart items
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Cek apakah user adalah admin (owner atau karyawan)
     */
    public function isAdmin()
    {
        return in_array($this->role, ['owner', 'karyawan']);
    }

    /**
     * Cek apakah user adalah owner
     */
    public function isOwner()
    {
        return $this->role === 'owner';
    }

    /**
     * Cek apakah user adalah customer
     */
    public function isCustomer()
    {
        return $this->role === 'customer';
    }

        public function orders()
        {
            return $this->hasMany(Order::class);
        }
    // Tambahkan method berikut ke app/Models/User.php

public function profile()
{
    return $this->hasOne(Profile::class);
}

public function homeImages()
{
    return $this->hasMany(HomeImage::class);
}

public function stocks()
{
    return $this->hasMany(Stock::class);
}
}