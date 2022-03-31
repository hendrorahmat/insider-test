<?php

namespace App\Http\Controllers;

use App\Repositories\GroupLeagueRepository;
use App\Repositories\GroupRepository;
use Exception;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * @var GroupRepository $groupRepository
     */
    private $groupRepository;

    /**
     * @var GroupLeagueRepository $groupLeagueRepository
     */
    private $groupLeagueRepository;

    public function __construct(
        GroupLeagueRepository $groupLeagueRepository,
        GroupRepository $groupRepository
    )
    {
        $this->groupRepository = $groupRepository;
        $this->groupLeagueRepository = $groupLeagueRepository;
    }

    /**
     * @throws Exception
     */
    public function show(int $groupId)
    {
        $group = $this->groupRepository->findOrFail($groupId);
        $data = $this->groupLeagueRepository->getClubsGroupedByGroupId($group->id);
        return view('groups.show', compact('data'));
    }
}
