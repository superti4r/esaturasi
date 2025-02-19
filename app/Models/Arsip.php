<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;

    protected $table = 'Arsip';

    protected $fillable = [
        'nama',
        'semester',
        'status',
    ];
}
