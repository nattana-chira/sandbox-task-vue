<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Technician extends Model
{
    protected $fillable = ['name'];

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }
}