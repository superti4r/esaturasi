<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ujian extends Model
{
    use HasFactory;

    protected $table = 'ujian';

    protected $fillable = [
        'identitas',
        'jenis',
        'mata_pelajaran_id',
        'kelas_id',
        'guru_id',
        'waktu_pengerjaan',
        'status',
        'token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ujian) {
            $ujian->identitas = Str::random(10);
            $ujian->token = Str::random(8);
        });
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function soal()
    {
        return $this->hasMany(SoalUjian::class, 'ujian_id');
    }
}
