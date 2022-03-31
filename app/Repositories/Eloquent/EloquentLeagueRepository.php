<?php

namespace App\Repositories\Eloquent;

use App\Models\League;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentLeagueRepository extends BaseRepository implements \App\Repositories\LeagueRepository
{
    /**
     * @inheritDoc
     */
    public function getModel(): Model
    {
        return new League();
    }

    /**
     * @return Collection
     */
    public function findLeague(): Collection
    {
        return $this->getModel()
            ->with('leagueClubsActive.club')
            ->whereHas('leagueClubsActive')
            ->where('name', '!=', League::CHAMPION_LEAGUE_NAME)
            ->get();
    }

    public function findChampionLeague(): Model
    {
        return $this->getModel()->where('name', League::CHAMPION_LEAGUE_NAME)->first();
    }
}
