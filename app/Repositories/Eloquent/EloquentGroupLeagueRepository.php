<?php

namespace App\Repositories\Eloquent;

use App\Models\Group;
use App\Models\GroupLeague;
use Illuminate\Database\Eloquent\Model;

class EloquentGroupLeagueRepository extends BaseRepository implements \App\Repositories\GroupLeagueRepository
{
	/**
	 * @inheritDoc
	 */
	public function getModel(): Model
	{
		return new GroupLeague();
	}
}
