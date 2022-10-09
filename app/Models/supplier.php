<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class supplier extends Model
{
    use HasFactory;
    public function supplier_orgs()
    {
        return $this->hasMany(supplier_org::class);
    }
}
