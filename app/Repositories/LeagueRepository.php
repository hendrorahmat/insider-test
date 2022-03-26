<?php

namespace App\Repositories;

use App\Models\League;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface LeagueRepository
{
    /**
     * @return Collection
     */
    public function findLeague(): Collection;

    /**
     * @return Model
     */
    public function findChampionLeagueId(): Model;
}
