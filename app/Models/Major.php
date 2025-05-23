<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;

    protected $table = 'major';

    protected $fillable = [
        'major_code',
        'name',
    ];

    public function classroom()
    {
        return $this->hasMany(Classroom::class);
    }
}
