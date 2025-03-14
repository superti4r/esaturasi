<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Siswa extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'siswa';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nisn',
        'nama',
        'tanggal_lahir',
        'tempat_lahir',
        'kelas_id',
        'jenis_kelamin',
        'alamat',
        'foto_profil',
        'tahun_masuk',
        'status',
        'email',
        'password',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanTugas::class);
    }
}
