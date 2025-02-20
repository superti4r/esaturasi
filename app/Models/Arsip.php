<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;

    protected $table = 'arsip';

    protected $fillable = [
        'nama',
        'semester',
        'status',
    ];

    public function mapel(){
        return $this->hasMany(MataPelajaran::class, 'arsip_id');
    }

    public function mapelperkelas(){
        return $this->hasMany(MapelPerKelas::class, 'arsip_id');
    }

    public function pembagianjadwal(){
        return $this->hasMany(PembagianJadwal::class, 'arsip_id');
    }

    public function bab(){
        return $this->hasMany(Bab::class, 'arsip_id');
    }

    public function pengumuman(){
        return $this->hasMany(Pengumuman::class, 'arsip_id');
    }

    public function scopeAktif($query){
        return $query->where('status', 'aktif');
    }
}
