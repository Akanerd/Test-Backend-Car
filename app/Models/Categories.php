<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $fillable = 
    [
        'name',
        'slug',
        'user_id',
    ];

    public function mobils()
    {
        return $this->hasMany(Mobil::class);
    }
}
