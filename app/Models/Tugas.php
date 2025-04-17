<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';
    protected $fillable = [
        'slug_id',
        'judul',
        'deskripsi',
        'file_path',
        'deadline'
    ];

    public function slug()
    {
        return $this->belongsTo(Slugs::class);
    }

    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanTugas::class);
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
