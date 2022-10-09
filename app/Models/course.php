<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'track_id',
        'description',
        'start_time',
        'end_time',
        'type'
    ];
    public function need_course()
    {
        return $this->hasMany(need_course::class);
    }
    public function enrolled_courses()
    {
        return $this->hasMany(EnrolledCourses::class);
    }
    public function track_courses()
    {
        return $this->hasMany(track_course::class);
    }
}
