<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
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
