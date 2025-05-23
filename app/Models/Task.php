<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $table = 'task';

    protected $fillable = [
        'slug_id',
        'title',
        'description',
        'file_path',
        'deadline',
    ];

    public function slug()
    {
        return $this->belongsTo(Slugs::class, 'slug_id');
    }

    public function submissions()
    {
        return $this->hasMany(SubmissionAndAssessment::class, 'task_id');
    }
}
