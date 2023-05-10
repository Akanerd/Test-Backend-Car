<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;
    
    protected $fillable = 
    [
        'title',
        'content',
        'image',
        'user_id',
        'category_id',
    ];

    //image

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => asset('storage/mobil/' . $image),
        );
    }

    public function categories()
    {
        return $this->belongsTo(Categories::class);
    }
}
