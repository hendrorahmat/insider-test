<?php

namespace App\Services;

use App\Entities\Club;
use App\Entities\Standing;
use App\Repositories\GroupLeagueRepository;

class ChampionshipPredictionService
{
    const TOTAL_MATCH_PER_CLUB_PER_GROUP = 6;

    /**
     * @var GroupLeagueRepository
     */
    private $groupLeagueRepository;

    public function __construct(GroupLeagueRepository $groupLeagueRepository)
    {
        $this->groupLeagueRepository = $groupLeagueRepository;
    }

    /**
     * @param int $groupId
     * @return Club[]
     */
    public function getPredictionByGroupId(int $groupId): array
    {
        $datas = $this->groupLeagueRepository->findWhere([
            'group_id' => $groupId
        ]);
        /** @var Club[] $listClubs */
        $listClubs = [];
        $allTotalPointClub = 0;

        /** @var int[] $totalPointPerClubs */
        $totalPointPerClubs = [];

        foreach ($datas as $data) {
            $club = new Club();
            $standing = new Standing();

            $standing->setId($data->id);
            $standing->setMatch($data->match);
            $standing->setTotalPoints($data->total_points);
            $standing->setWin($data->win);
            $standing->setDraw($data->draw);
            $standing->setLoose($data->loose);
            $standing->setGoalFor($data->goal_for);
            $standing->setGoalAgainst($data->goal_against);
            $standing->setGoalDiff($data->goal_difference);

            $allTotalPointClub += $standing->getTotalPoints();

            $club->setId($data->club->id);
            $club->setName($data->club->name);
            $club->setStanding($standing);

            $listClubs[] = $club;
            $totalPointPerClubs[$club->getId()] = $club->getStanding()->getTotalPoints();
        }

        $totalClub = count($listClubs);

        foreach ($listClubs as $listClub) {
            $currentPoint = $listClub->getStanding()->getTotalPoints();

            if ($this->isPointAheadByNine($totalPointPerClubs, $listClub->getId())) {
                $listClub->getStanding()->setChampionshipPercentage(100);
                $listClubs = $this->setAnotherClubWinnerZero($listClubs, $listClub);
                break;
            }

            if ($currentPoint !== 0 && $totalClub !== 0) {
                $pointPerClub = $allTotalPointClub / $totalClub;
                $prediction = ($currentPoint / $totalClub) / $pointPerClub * 100;
                $listClub->getStanding()->setChampionshipPercentage($prediction);
            }
        }


        foreach ($listClubs as $indexC => $lc) {
            $firstTotalPoint = $lc->getStanding()->getTotalPoints();
            foreach ($listClubs as $indexL => $l) {
                $secondTotalPoint = $l->getStanding()->getTotalPoints();
                if ($indexC !== $indexL && $secondTotalPoint < $firstTotalPoint ) {
                    $temp = $listClubs[$indexC];
                    $listClubs[$indexC] = $listClubs[$indexL];
                    $listClubs[$indexL] = $temp;
                }
            }
        }

        return $listClubs;
    }

    /**
     * @param Club[] $listClubs
     * @param Club $clubWinner
     * @return Club[]
     */
    public function setAnotherClubWinnerZero(array $listClubs, Club $clubWinner): array
    {
        foreach ($listClubs as $index => $listClub) {
            if ($listClub->getId() !== $clubWinner->getId()) {
                $listClub->getStanding()->setChampionshipPercentage(0);
                $listClubs[$index] = $listClub;
            }
        }

        return $listClubs;
    }

    /**
     * @param int[] $totalPointPerClubs
     * @param int $clubId
     * @return bool
     */
    private function isPointAheadByNine(array $totalPointPerClubs, int $clubId): bool
    {
        $clubPoint = $totalPointPerClubs[$clubId];
        $totalPointAutoWin = 9;
        $totalClubs = count($totalPointPerClubs) - 1;

        foreach ($totalPointPerClubs as $idClub => $totalPointClub) {
            if ($idClub !== $clubId && ($clubPoint - $totalPointClub) >= $totalPointAutoWin) {
                --$totalClubs;
            }
        }

        return $totalClubs === 0;
    }
}
