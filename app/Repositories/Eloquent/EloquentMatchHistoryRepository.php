<?php

namespace App\Repositories\Eloquent;

use App\Models\MatchHistory;
use App\Repositories\MatchHistoryRepository;
use Illuminate\Database\Eloquent\Model;

class EloquentMatchHistoryRepository extends BaseRepository implements MatchHistoryRepository
{
    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return new MatchHistory();
    }
}
