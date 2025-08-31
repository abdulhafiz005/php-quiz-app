<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['text'];

    public function options(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Option::class);
    }
}