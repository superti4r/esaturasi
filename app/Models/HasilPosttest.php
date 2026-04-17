<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilPosttest extends Model
{
    protected $table = 'hasil_posttests';

    protected $fillable = [
        'siswa_id',
        'posttest_id',
        'nilai',
        'lulus'
    ];

    // relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // relasi ke posttest
    public function posttest()
    {
        return $this->belongsTo(Posttest::class);
    }
}