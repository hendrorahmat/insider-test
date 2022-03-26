<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code'
    ];

    public function clubs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Club::class);
    }
}
