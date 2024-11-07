<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use HasFactory;

    // Explicitly specify the table name
    protected $table = 'social_media';

    protected $fillable = [
        'type',
        'label',
        'url',
    ];
}
