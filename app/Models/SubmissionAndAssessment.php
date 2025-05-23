<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubmissionAndAssessment extends Model
{
    use HasFactory;

    protected $table = 'submission_and_assessment';

    protected $fillable = [
        'task_id',
        'student_id',
        'file_path',
        'assignment',
        'status',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
