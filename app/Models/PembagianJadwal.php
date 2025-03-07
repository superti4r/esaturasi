<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembagianJadwal extends Model
{
    use HasFactory;

    protected $table = 'pembagian_jadwal';
    protected $fillable = [
        'kelas_id',
        'mata_pelajaran_id',
        'guru_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'arsip_id',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function arsip(){
        return $this->belongsTo(Arsip::class, 'arsip_id');
    }

    public function jadwalMataPelajaran(){
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function slugs()
    {
        return $this->hasMany(Slugs::class, 'jadwal_id');
    }
}
