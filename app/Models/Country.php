<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';
    protected $fillable = [
        'name',
        'shortcode',
        'phone_code',
    ];

    // Relationships
    public function phones() {
        return $this->hasMany(\App\Models\Phone::class, 'user_id', 'id');
    }
}
