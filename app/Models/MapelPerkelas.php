<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapelPerKelas extends Model
{
    use HasFactory;

    protected $table = 'mapel_perkelas';

    protected $fillable = [
        'kelas_id',
        'mata_pelajaran_id',
        'guru_id',
        'arsip_id',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id')->where('role', 'guru');
    }

    public function arsip(){
        return $this->belongsTo(Arsip::class, 'arsip_id');
    }
}
