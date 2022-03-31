<?php

namespace App\Repositories\Eloquent;

use App\Models\Club;
use App\Models\League;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentClubRepository extends BaseRepository implements \App\Repositories\ClubRepository
{
    public function getModel(): Model
    {
        return new Club();
    }

    /**
     * @param array $clubIds
     * @return int[]
     */
    public function getCountryFromClubIds(array $clubIds): array
    {
        $clubs = $this->getModel()->whereIn('id', $clubIds)->get();
        $countryIds = [];

        foreach ($clubs as $club) {
            $countryIds[] = $club->nation_id;
        }

        return $countryIds;
    }
}
