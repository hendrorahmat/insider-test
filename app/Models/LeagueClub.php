<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeagueClub extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'league_id',
        'club_id',
        'total_points',
        'match',
        'win',
        'draw',
        'loose',
        'goal_for',
        'goal_against',
        'goal_difference',
        'session_id'
    ];

    public function session(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    public function club(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
