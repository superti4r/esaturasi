<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posttest extends Model
{
    protected $table = 'posttests';

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
        return $this->hasMany(SoalPosttest::class);
    }

    //relasi ke hasil siswa
    public function hasil()
    {
        return $this->hasMany(HasilPosttest::class);
    }
}