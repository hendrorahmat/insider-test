<?php

namespace App\Repositories\Eloquent;

use App\Models\GroupMatch;
use App\Repositories\GroupMatchRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EloquentGroupMatchRepository extends BaseRepository implements GroupMatchRepository
{
    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return new GroupMatch();
    }

    /**
     * @param int $groupId
     * @return Collection
     */
    public function findMatchThatNotPlayedByGroupId(int $groupId): Collection
    {
        return $this->getModel()->doesntHave('matchHistory')->where('group_id', $groupId)->get();
    }

    /**
     * @param int $groupId
     * @return Collection
     */
    public function getHistoryClubMatchByGroupId(int $groupId): Collection
    {
        return $this->getModel()->with('matchHistory.match.club')
            ->where('group_id', $groupId)
            ->whereHas('matchHistory')
            ->get();
    }
}
