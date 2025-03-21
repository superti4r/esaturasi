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
            do {
                $token = Str::random(8);
            } while (self::where('token', $token)->exists());

            $ujian->token = $token;
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

    public function jumlahSoal()
    {
        return $this->soal()->count();
    }

    public function isAktif()
    {
        return $this->status;
    }
}
