<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalPretest extends Model
{
    protected $table = 'soal_pretests';

    protected $fillable = [
        'pretest_id',
        'soal',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'jawaban'
    ];

    // relasi ke pretest
    public function pretest()
    {
        return $this->belongsTo(Pretest::class);
    }
}