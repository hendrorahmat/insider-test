<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlayerClub extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'player_id',
        'club_id'
    ];
}
