<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChamberMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role_es',
        'role_en',
        'img_path'
    ];

    public function getImgPathAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
