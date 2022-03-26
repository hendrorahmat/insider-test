<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotsClub extends Model
{
    use HasFactory;

    protected $fillable = [
        'pots_id',
        'club_id'
    ];
}
