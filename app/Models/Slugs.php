<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slugs extends Model
{
    use HasFactory;

    protected $table = 'slugs';
    protected $fillable = [
        'schedule_id',
        'title',
        'slug',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                do {
                    $slug = Str::random(7);
                } while (self::where('slug', $slug)->exists());

                $model->slug = $slug;
            }
        });
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function subjectMatter()
    {
        return $this->hasOne(SubjectMatter::class, 'slug_id');
    }

    public function task()
    {
        return $this->hasOne(Task::class, 'slug_id');
    }
}
