<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slugs extends Model
{
    use HasFactory;

    protected $table = 'slugs';
    protected $fillable = [
        'jadwal_id',
        'judul',
        'slug'
    ];

    public function jadwal()
    {
        return $this->belongsTo(PembagianJadwal::class, 'jadwal_id');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    public function materi()
    {
        return $this->hasMany(Materi::class);
    }
}
