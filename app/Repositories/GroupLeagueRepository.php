<?php

namespace App\Repositories;

use App\Entities\Club;
use App\Entities\GroupChampionLeague;
use Illuminate\Database\Eloquent\Collection;

interface GroupLeagueRepository extends BaseRepositoryInterface
{
    /**
     * @param int $groupId
     * @return GroupChampionLeague
     */
    public function getClubsGroupedByGroupId(int $groupId): GroupChampionLeague;

    /**
     * @param int $clubId
     * @param int $groupId
     * @param int $leagueId
     * @return Collection
     */
    public function findOpponentClubId(int $clubId, int $groupId, int $leagueId): Collection;

    /**
     * @param int[] $clubIds
     * @param int $leagueId
     * @return Club
     */
    public function findHighestPointClubByClubIds(array $clubIds, int $leagueId): Club;
}
