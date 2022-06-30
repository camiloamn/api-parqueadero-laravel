<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tDocumentos extends Model
{
    protected $table = 'tDocumentos';
    public $timestamps = false; //bloqueo por defecto el update at porque laravel los necesita siempre
}
