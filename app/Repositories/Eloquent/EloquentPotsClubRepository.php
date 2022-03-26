<?php

namespace App\Repositories\Eloquent;

use App\Models\Pots;
use App\Models\PotsClub;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EloquentPotsClubRepository extends BaseRepository implements \App\Repositories\PotsClubRepository
{
	/**
	 * @inheritDoc
	 */
	public function getModel(): Model
	{
		return new PotsClub();
	}

	public function findAll(): Collection
	{
		return $this->getModel()->all();
	}

    /**
     * @param int $potsId
     * @return Model
     */
    public function findClubRandomByPotsId(int $potsId): Model
    {
        return $this->getModel()->where('pots_id', $potsId)->inRandomOrder()->first();
    }
}
