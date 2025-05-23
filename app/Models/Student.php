<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table = 'student';

    protected $fillable = [
        'nisn',
        'name',
        'date_of_birth',
        'place_of_birth',
        'classroom_id',
        'gender',
        'address',
        'email',
        'password',
        'avatar_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'password' => 'hashed'
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function submissions()
    {
        return $this->hasMany(SubmissionAndAssessment::class);
    }
}
