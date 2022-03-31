<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupLeague extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'group_id',
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

    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function league(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    public function club(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
