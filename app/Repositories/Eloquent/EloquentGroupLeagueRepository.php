<?php

namespace App\Repositories\Eloquent;

use App\Entities\Club;
use App\Entities\Group;
use App\Entities\GroupChampionLeague;
use App\Entities\Standing;
use App\Models\GroupLeague;
use App\Repositories\GroupLeagueRepository;
use App\Repositories\GroupRepository;
use App\Repositories\LeagueRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EloquentGroupLeagueRepository extends BaseRepository implements GroupLeagueRepository
{
    /**
     * @var LeagueRepository $leagueRepository
     */
    private $leagueRepository;

    /**
     * @var GroupRepository
     */
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository, LeagueRepository $leagueRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->leagueRepository = $leagueRepository;
    }

    /**
     * @param int $groupId
     * @return GroupChampionLeague
     * @throws Exception
     */
    public function getClubsGroupedByGroupId(int $groupId): GroupChampionLeague
    {
        $championLeague = $this->leagueRepository->findChampionLeague();
        $groupModel = $this->groupRepository->find($groupId);

        $groupChampionLeague = new GroupChampionLeague();
        $datas = $this->getModel()
            ->with([
                'league',
                'club',
                'group'
            ])
            ->where('league_id', $championLeague->id)
            ->where('group_id', $groupId)
            ->latest('total_points')
            ->get();

        $group = new Group();
        $group->setId($groupId);
        $group->setName($groupModel->name);

        foreach ($datas as $data) {

            $standing = new Standing();
            $standing->setId($data->id);
            $standing->setDraw($data->draw);
            $standing->setGoalAgainst($data->goal_against);
            $standing->setGoalDiff($data->goal_difference);
            $standing->setWin($data->win);
            $standing->setDraw($data->draw);
            $standing->setMatch($data->match);
            $standing->setTotalPoints($data->total_points);
            $standing->setLoose($data->loose);
            $standing->setGoalFor($data->goal_for);

            $groupChampionLeague->setGroup($group);
            $club = new Club();
            $club->setId($data->club->id);
            $club->setName($data->club->name);
            $club->setStanding($standing);

            $groupChampionLeague->addClub($club);
        }
        return $groupChampionLeague;
    }

    /**
     * @inheritDoc
     */
    public function getModel(): Model
    {
        return new GroupLeague();
    }

    public function findOpponentClubId(int $clubId, int $groupId, int $leagueId): Collection
    {
        return $this->getModel()
            ->where('club_id', '!=', $clubId)
            ->where('group_id', $groupId)
            ->where('league_id', $leagueId)
            ->get();
    }

    /**
     * @param int[] $clubIds
     * @param int $leagueId
     * @return Club
     */
    public function findHighestPointClubByClubIds(array $clubIds, int $leagueId): Club
    {
        $data = $this->getModel()
            ->with('club')
            ->whereIn('club_id', $clubIds)
            ->where('league_id', $leagueId)
            ->latest('total_points')
            ->first();

        $standing = new Standing();

        $club = new Club();
        $club->setId($data->club_id);
        $club->setName($data->club->name);

        $standing->setTotalPoints($data->total_points);
        $standing->setMatch($data->match);
        $standing->setWin($data->win);
        $standing->setDraw($data->draw);
        $standing->setGoalFor($data->goal_for);
        $standing->setGoalAgainst($data->goal_against);
        $standing->setGoalDiff($data->goal_difference);

        $club->setStanding($standing);

        return $club;
    }
}
