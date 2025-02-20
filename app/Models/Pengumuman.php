<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';
    protected $fillable = [
        'judul_pengumuman',
        'content_pengumuman',
        'arsip_id',
    ];

    public function arsip(){
        return $this->belongsTo(Arsip::class, 'arsip_id');
    }
}
