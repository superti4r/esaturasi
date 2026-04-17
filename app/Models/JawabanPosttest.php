<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanPosttest extends Model
{
    protected $table = 'jawaban_posttests';

    protected $fillable = [
        'posttest_id',
        'soal_posttest_id',
        'student_id',
        'jawaban_siswa',
        'is_benar',
    ];

    // relasi ke posttest
    public function posttest()
    {
        return $this->belongsTo(Posttest::class);
    }

    // relasi ke soal
    public function soal()
    {
        return $this->belongsTo(SoalPosttest::class, 'soal_posttest_id');
    }

    // relasi ke student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}