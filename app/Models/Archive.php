<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Archive extends Model
{
    use HasFactory;

    protected $table = 'archive';

    protected $fillable = [
        'name',
        'semester',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function announcement()
    {
        return $this->hasMany(Announcement::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
