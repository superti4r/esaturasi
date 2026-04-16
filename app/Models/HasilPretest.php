<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilPretest extends Model
{
    protected $table = 'hasil_pretests';

    protected $fillable = [
        'siswa_id',
        'pretest_id',
        'nilai',
        'lulus'
    ];

    // relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // relasi ke pretest
    public function pretest()
    {
        return $this->belongsTo(Pretest::class);
    }
}