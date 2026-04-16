<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanPretest extends Model
{
    protected $table = 'jawaban_pretests';

    protected $fillable = [
        'pretest_id',
        'soal_pretest_id',
        'student_id',
        'jawaban_siswa',
        'is_benar',
    ];

    // relasi ke pretest
    public function pretest()
    {
        return $this->belongsTo(Pretest::class);
    }

    // relasi ke soal
    public function soal()
    {
        return $this->belongsTo(SoalPretest::class, 'soal_pretest_id');
    }

    // relasi ke student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}