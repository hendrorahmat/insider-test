<?php

namespace App\Services;

use App\Entities\GroupChampionLeague;
use App\Models\League;
use App\Repositories\GroupLeagueRepository;
use App\Repositories\GroupRepository;
use App\Repositories\LeagueRepository;
use App\Repositories\PotsClubRepository;
use App\Repositories\PotsRepository;

class ChampionLeagueService
{
    /**
     * @var LeagueRepository
     */
    private $leagueRepository;

    /**
     * @var PotsRepository
     */
    private $potsRepository;

    /**
     * @var PotsClubRepository
     */
    private $potsClubRepository;

    /**
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * @var GroupLeagueRepository
     */
    private $groupLeagueRepository;

    public function __construct(
        LeagueRepository      $leagueRepository,
        PotsRepository        $potsRepository,
        PotsClubRepository    $potsClubRepository,
        GroupRepository       $groupRepository,
        GroupLeagueRepository $groupLeagueRepository
    )
    {
        $this->leagueRepository = $leagueRepository;
        $this->potsRepository = $potsRepository;
        $this->potsClubRepository = $potsClubRepository;
        $this->groupRepository = $groupRepository;
        $this->groupLeagueRepository = $groupLeagueRepository;
    }

    public function generateChampionLeague(): void
    {
        $pots = $this->potsRepository->findAll()->toArray();
        $leagues = $this->leagueRepository->findLeague();

        foreach ($pots as $index => $pot) {
            $pots[$index]['club'] = [];
            foreach ($leagues as $league) {
                $leagueClub = $league->leagueClubsActive[$index];
                $pots[$index]['club'][] = $leagueClub->club->id;
            }
        }

        foreach ($pots as $pot) {
            foreach ($pot['club'] as $clubId) {
                $this->potsClubRepository->firstOrCreate([
                    'pots_id' => $pot['id'],
                    'club_id' => $clubId
                ]);
            }
        }

        $groups = $this->groupRepository->all();
        if ($this->groupLeagueRepository->count() !== League::MAX_CHAMPION_LEAGUE_GROUP_PARTICIPANT) {
            foreach ($groups as $group) {
                foreach ($pots as $pot) {
                    $this->findGroupLeague($pot['id'], $group->id);
                }
            }
        }
    }

    private function findGroupLeague(int $potsId, int $groupId): void
    {
        $potsClub = $this->potsClubRepository->findClubRandomByPotsId($potsId);
        $championLeagueId = $this->leagueRepository->findChampionLeague()->id;
        $isClubAlreadyAssigned = $this->groupLeagueRepository->findOneWhere([
            'league_id' => $championLeagueId,
            'club_id' => $potsClub['club_id']
        ]);

        if (is_null($isClubAlreadyAssigned)) {
            $groupLeagueData = [
                'group_id' => $groupId,
                'league_id' => $championLeagueId,
                'club_id' => $potsClub['club_id']
            ];

            $this->groupLeagueRepository->firstOrCreate($groupLeagueData);
        } else {
            $this->findGroupLeague($potsId, $groupId);
        }
    }

    /**
     * @return GroupChampionLeague[]
     */
    public function getClubsChampionLeague(): array
    {
        $groups = $this->groupRepository->all();

        /** @var GroupChampionLeague[] $groupChampionLeagues */
        $groupChampionLeagues = [];
        foreach ($groups as $group) {
            $groupChampionLeague = $this->groupLeagueRepository->getClubsGroupedByGroupId($group->id);
            $groupChampionLeagues[] = $groupChampionLeague;
        }
        return $groupChampionLeagues;
    }
}
