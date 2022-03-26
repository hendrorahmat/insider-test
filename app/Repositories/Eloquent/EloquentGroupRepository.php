<?php

namespace App\Repositories\Eloquent;

use App\Models\Group;
use Illuminate\Database\Eloquent\Model;

class EloquentGroupRepository extends BaseRepository implements \App\Repositories\GroupRepository
{
	/**
	 * @inheritDoc
	 */
	public function getModel(): Model
	{
		return new Group();
	}
}
