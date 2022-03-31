<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface GroupMatchRepository extends BaseRepositoryInterface
{
    /**
     * @param int $groupId
     * @return Collection
     */
    public function findMatchThatNotPlayedByGroupId(int $groupId): Collection;

    /**
     * @param int $groupId
     * @return Collection
     */
    public function getHistoryClubMatchByGroupId(int $groupId): Collection;
}
