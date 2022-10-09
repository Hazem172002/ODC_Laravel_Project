<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class track_course extends Model
{
    use HasFactory;
    public function courses()
    {
        return $this->belongsTo(course::class);
    }
}
