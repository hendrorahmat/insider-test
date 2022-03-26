<?php

namespace App\Repositories\Eloquent;

use App\Models\Pots;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EloquentPotsRepository extends BaseRepository implements \App\Repositories\PotsRepository
{
	/**
	 * @inheritDoc
	 */
	public function getModel(): Model
	{
		return new Pots();
	}

	public function findAll(): Collection
	{
		return $this->getModel()->all();
	}
}
