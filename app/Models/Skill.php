<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    protected $fillable = ['name', 'technician_id'];

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }
}