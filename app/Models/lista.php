<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lista extends Model
{
    use HasFactory;
    protected $table = 'listas';
    public $timestamps = false;
}
