<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilPretest extends Model
{
    protected $table = 'hasil_pretests';

    protected $fillable = [
        'student_id',
        'pretest_id',
        'nilai',
        'lulus',
    ];

    protected $casts = [
        'lulus' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function pretest()
    {
        return $this->belongsTo(Pretest::class);
    }
}