<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';
    protected $fillable = [
        'slug_id',
        'judul',
        'deskripsi',
        'file_path'
    ];

    public function slug()
    {
        return $this->belongsTo(Slugs::class);
    }
}
