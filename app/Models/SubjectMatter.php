<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubjectMatter extends Model
{
    use HasFactory;

    protected $table = 'subject_matter';

    protected $fillable = [
        'slug_id',
        'title',
        'description',
        'file_path',
    ];

    public function slug()
    {
        return $this->belongsTo(Slugs::class, 'slug_id');
    }
}
