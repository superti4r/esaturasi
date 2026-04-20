<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilPosttest extends Model
{
    protected $table = 'hasil_posttests';

    protected $fillable = [
        'student_id',
        'posttest_id',
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

    public function posttest()
    {
        return $this->belongsTo(Posttest::class);
    }
}