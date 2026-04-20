<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pretest extends Model
{
    protected $table = 'pretests';

    protected $fillable = [
        'slug_id',
        'judul',
        'kkm',
        'file_soal',
        'waktu_mulai',
        'waktu_selesai'
    ];

    protected $casts = [
        'waktu_mulai'   => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    // relasi ke slug (bab/materi)
    public function slug()
    {
        return $this->belongsTo(Slugs::class);
    }

    //relasi ke soal
    public function soal()
    {
        return $this->hasMany(SoalPretest::class);
    }

    //relasi ke hasil siswa
    public function hasil()
    {
        return $this->hasMany(HasilPretest::class);
    }

    public function jawabanSiswa()
{
    return $this->hasMany(JawabanPretest::class);
}
}