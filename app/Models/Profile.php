<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'owner_name',
        'owner_photo',
        'owner_description',
        'history',
        'experience',
        'location_name',
        'latitude',
        'longitude'
    ];
}
