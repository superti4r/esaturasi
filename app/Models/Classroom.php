<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends Model
{
    use HasFactory;

    protected $table = 'classroom';

    protected $fillable = [
        'name',
        'major_id',
    ];

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
