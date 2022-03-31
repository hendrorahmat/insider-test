<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatchHistory extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_MATCH_DRAW = 'draw';

    const TYPE_MATCH_WIN = 'win';

    const TYPE_MATCH_LOOSE = 'loose';

    protected $fillable = [
        'match_id',
        'points',
        'type',
        'goal_for',
        'goal_against',
    ];

    public function match(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MatchModel::class);
    }
}
