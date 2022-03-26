<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class League extends Model
{
    use HasFactory, SoftDeletes;

    const CHAMPION_LEAGuE_NAME = 'Champion League';

    protected $fillable = [
        'ranking_points',
        'name',
        'nation_id'
    ];

    public function leagueClubsActive()
    {
        return $this->hasMany(LeagueClub::class)
            ->whereHas('session', function (Builder $query) {
            $query->where('is_active', 1);
        })->orderBy('total_points', 'DESC');
    }
}
