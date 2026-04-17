<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalPosttest extends Model
{
    protected $table = 'soal_posttests';

    protected $fillable = [
        'posttest_id',
        'soal',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'jawaban',
        'poin'
    ];

    // relasi ke posttest
    public function posttest()
    {
        return $this->belongsTo(Posttest::class);
    }
}