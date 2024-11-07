<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title_es',
        'title_en',
        'description_es',
        'description_en',
        'contact_name',
        'phone',
        'img_path',
    ];

    public $timestamps = true;

    public function getImgPathAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
