<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    // Define el nombre de la tabla en la base de datos
    protected $table = 'contacts';

    // Define los campos que se pueden llenar de forma masiva
    protected $fillable = [
        'phone',
        'email',
        'address',
        'lat',
        'long',
    ];

    // Define los campos que se deben ocultar en las respuestas JSON
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // Define los campos que deben ser convertidos a tipos específicos
    protected $casts = [
        'lat' => 'double',
        'long' => 'double',
    ];
}
