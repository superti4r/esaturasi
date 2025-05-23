<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $table = 'announcement';

    protected $fillable = [
        'title',
        'content',
        'archive_id',
    ];

    public function archive(): BelongsTo
    {
        return $this->belongsTo(Archive::class);
    }
}
