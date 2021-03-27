<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    protected $table = 'phones';
    protected $fillable = [
        'user_id',
        'country_id',
        'phone',
        'enabled',
    ];

    // Relationships
    public function user() {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function country() {
        return $this->belongsTo(\App\Models\Country::class, 'country_id', 'id');
    }

    // Accessors
//    public function getPhoneAttribute($val) {
//        return $this->country->phone_code.' '.$val;
//    }
}
