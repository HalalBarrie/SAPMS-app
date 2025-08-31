<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'credit_hours',
        'grade',
        'semester',
        'academic_year',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
