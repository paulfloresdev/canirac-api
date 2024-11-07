<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_es',
        'title_en',
        'description_es',
        'description_en',
        'price',
        'date',
        'time',
        'address',
        'lat',
        'long',
        'img_path', // Campo único para las imágenes
    ];

    // Si deseas utilizar casts para los campos
    protected $casts = [
        'price' => 'float',
        'date' => 'date',
    ];

    /**
     * Obtener la URL completa de la imagen.
     *
     * @param  string|null  $value
     * @return string|null
     */
    public function getImgPathAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
