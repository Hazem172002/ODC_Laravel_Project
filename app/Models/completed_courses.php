<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class completed_courses extends Model
{
    use HasFactory;
    public function course_skills()
    {
        return $this->hasMany(course_skills::class, 'course_id');
    }
}
