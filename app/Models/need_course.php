<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class need_course extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'needs'
    ];
    public function course()
    {
        return $this->belongsTo(course::class);
    }
}
