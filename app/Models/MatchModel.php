<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatchModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'matches';

    const TYPE_HOME = 'home';

    const TYPE_AWAY = 'away';

    const MAX_HOME_MATCH = 3;

    const MAX_AWAY_MATCH = 3;

    const TOTAL_MATCH_WEEK = 6;

    protected $fillable = [
        'match_uuid',
        'club_id',
        'league_id',
        'date_match',
        'type'
    ];

    public function club(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function matchHistory(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MatchHistory::class, 'match_id');
    }
}
