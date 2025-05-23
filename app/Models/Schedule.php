<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $table = 'schedule';

    protected $fillable = [
        'classroom_id',
        'subject_id',
        'teacher_id',
        'archive_id',
        'schedule',
    ];

    protected $casts = [
        'schedule' => 'array',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function archive(): BelongsTo
    {
        return $this->belongsTo(Archive::class);
    }
}
