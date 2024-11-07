<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'size_es',
        'size_en',
        'description_es',
        'description_en',
        'price1',
        'price2',
        'price3'
    ];
}
