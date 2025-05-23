<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subject';

    protected $fillable = [
        'name',
        'archive_id',
    ];

    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
