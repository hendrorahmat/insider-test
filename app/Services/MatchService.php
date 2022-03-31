<?php

namespace App\Services;

use App\Entities\Club;
use App\Entities\Club as ClubEntity;
use App\Entities\ClubMatch;
use App\Entities\Games;
use App\Entities\Standing;
use App\Models\MatchHistory;
use App\Models\MatchModel;
use App\Repositories\GroupLeagueRepository;
use App\Repositories\GroupMatchRepository;
use App\Repositories\LeagueRepository;
use App\Repositories\MatchHistoryRepository;
use App\Repositories\MatchRepository;
use App\Repositories\PotsClubRepository;
use App\Services\GameMatch\Goal;
use App\Services\GameMatch\HomeAway;
use App\Services\GameMatch\IsFromPotOne;
use App\Services\GameMatch\TeamStrength;
use App\Services\GameMatch\TotalPoint;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;

class MatchService
{
    /**
     * @var MatchRepository
     */
    private $matchRepository;

    /**
     * @var GroupLeagueRepository
     */
    private $groupLeagueRepository;

    /**
     * @var MatchHistoryRepository
     */
    private $matchHistoryRepository;

    /**
     * @var LeagueRepository
     */
    private $leagueRepository;

    /**
     * @var GroupMatchRepository
     */
    private $groupMatchRepository;

    public function __construct(
        MatchRepository        $matchRepository,
        GroupLeagueRepository  $groupLeagueRepository,
        MatchHistoryRepository $matchHistoryRepository,
        LeagueRepository       $leagueRepository,
        GroupMatchRepository   $groupMatchRepository
    )
    {
        $this->matchRepository = $matchRepository;
        $this->groupLeagueRepository = $groupLeagueRepository;
        $this->matchHistoryRepository = $matchHistoryRepository;
        $this->leagueRepository = $leagueRepository;
        $this->groupMatchRepository = $groupMatchRepository;
    }

    /**
     * @param int $groupId
     * @return Games[]
     */
    public function getMatchByGroupId(int $groupId): array
    {
        $clubIds = $this->getClubIdsByGroupId($groupId);
        $firstMatch = $this->matchRepository->findOneWhereFirstDateMatchClub($clubIds)->date_match;
        $lastMatch = $this->matchRepository->findOneWhereLatestDateMatchClub($clubIds)->date_match;

        $firstMatch = Carbon::parse($firstMatch)->startOfDay();
        $firstMatch = $firstMatch->copy()->subDays($firstMatch->dayOfWeek);
        $lastMatch = Carbon::parse($lastMatch)->endOfWeek()->endOfDay();

        $dateMatch = $firstMatch->copy();

        /** @var Games[] $listOfGames */
        $listOfGames = [];
        for ($i = 1; $i <= $lastMatch->diffInWeeks($firstMatch); $i++) {
            $games = new Games();
            $games->setName('Week ' . $i);

            $clubs = $this->matchRepository->findSpecificDateClubIds([
                $dateMatch->startOfDay()->copy(),
                $dateMatch->addWeek()->copy()
            ], $clubIds);

            $clubMatch = new ClubMatch();
            foreach ($clubs as $club) {
                $clubEntity = new ClubEntity();
                $clubEntity->setName($club->club->name);
                $clubEntity->setId($club->club->id);

                if ($club->type === MatchModel::TYPE_HOME) {
                    $clubMatch->setHome($clubEntity);
                } else {
                    $clubMatch->setAway($clubEntity);
                }

                $clubMatch->setMatchUuid($club->match_uuid);
                $clubMatch->setDate(Carbon::parse($club->date_match));

                if ($clubMatch->getAway() && $clubMatch->getHome()) {
                    $games->addClub($clubMatch);
                    $clubMatch = new ClubMatch();
                }
            }
            $listOfGames[] = $games;
        }

        return $listOfGames;
    }

    /**
     * @param int $groupId
     * @return int[]
     */
    public function getClubIdsByGroupId(int $groupId): array
    {
        $datas = $this->groupLeagueRepository->findWhere(['group_id' => $groupId]);
        $clubIds = [];
        foreach ($datas as $data) {
            $clubIds[] = $data->club_id;
        }
        return $clubIds;
    }

    /**
     * @param int $groupId
     * @return Games
     */
    public function findCurrentMatch(int $groupId): Games
    {
        $clubIds = $this->getClubIdsByGroupId($groupId);
        return $this->matchRepository->findCurrentMatchClubIds($clubIds);
    }

    /**
     * @param int[] $matchUuids
     * @param int $groupId
     * @return void
     * @throws BindingResolutionException
     * @throws \Exception
     */
    public function playMatch(array $matchUuids, int $groupId): void
    {

        foreach ($matchUuids as $matchUuid) {
            $games = new Games();
            $matches = $this->matchRepository->getMatchByUuids([$matchUuid]);
            $clubMatch = new ClubMatch();

            foreach ($matches as $match) {
                $club = new Club();
                $club->setId($match->club->id);
                $club->setName($match->club->name);
                $club->setStrength($match->club->strength);

                $clubMatch->setMatchUuid($match->match_uuid);
                if ($match->type === MatchModel::TYPE_HOME) {
                    $clubMatch->setAway($club);
                } else {
                    $clubMatch->setHome($club);
                }

                if ($clubMatch->getAway() && $clubMatch->getHome()) {
                    $games->addClub($clubMatch);
                    $clubMatch = new ClubMatch();
                }
            }

            /** @var GroupLeagueRepository $groupLeagueRepository */
            $groupLeagueRepository = app()->make(GroupLeagueRepository::class);
            $potClubRepository = app()->make(PotsClubRepository::class);

            foreach ($games->getClubs() as $clubMatch) {

                $gameService = new GameService($clubMatch);
                $gameService->addParameterPrediction(
                    new TeamStrength($clubMatch),
                    new TotalPoint($clubMatch, $groupLeagueRepository),
                    new HomeAway($clubMatch),
                    new Goal($clubMatch, $groupLeagueRepository),
                    new IsFromPotOne($clubMatch, $potClubRepository)
                );

                $clubWinner = $gameService->getTheWinner();
                $clubLoose = $gameService->getLooser();

                if (is_null($clubWinner) && is_null($clubLoose)) {
                    $this->createDrawMatch($clubMatch, $groupId);
                } else {
                    $goalRand = random_int(0, 6);

                    $this->createMatchHistory(
                        $clubWinner,
                        $groupId,
                        $clubMatch->getMatchUuid(),
                        MatchHistory::TYPE_MATCH_WIN,
                        $goalRand + 1, $goalRand
                    );
                    $this->createMatchHistory(
                        $clubLoose,
                        $groupId,
                        $clubMatch->getMatchUuid(),
                        MatchHistory::TYPE_MATCH_LOOSE,
                        $goalRand, $goalRand + 1
                    );
                }
            }
        }
    }

    /**
     * @throws \Exception
     */
    private function createDrawMatch(ClubMatch $clubMatch, int $groupId)
    {
        $goalRand = random_int(0, 10);
        $leagueChampion = $this->leagueRepository->findChampionLeague();

        $matchHome = $this->matchRepository->findOneWhere([
            'match_uuid' => $clubMatch->getMatchUuid(),
            'club_id' => $clubMatch->getHome()->getId()
        ]);
        $matchAway = $this->matchRepository->findOneWhere([
            'match_uuid' => $clubMatch->getMatchUuid(),
            'club_id' => $clubMatch->getAway()->getId()
        ]);

        $this->matchHistoryRepository->createBulk([
            [
                'match_id' => $matchHome->id,
                'points' => 1,
                'type' => MatchHistory::TYPE_MATCH_DRAW,
                'goal_for' => $goalRand,
                'goal_against' => $goalRand,
            ],
            [
                'match_id' => $matchAway->id,
                'points' => 1,
                'type' => MatchHistory::TYPE_MATCH_DRAW,
                'goal_for' => $goalRand,
                'goal_against' => $goalRand,
            ],
        ]);

        $groupLeagueHome = $this->groupLeagueRepository->findOneWhere([
            'group_id' => $groupId,
            'club_id' => $clubMatch->getHome()->getId(),
            'league_id' => $leagueChampion->id
        ]);

        $groupLeagueAway = $this->groupLeagueRepository->findOneWhere([
            'group_id' => $groupId,
            'club_id' => $clubMatch->getAway()->getId(),
            'league_id' => $leagueChampion->id
        ]);

        $this->updateByPrimaryKeyId($groupLeagueHome, $goalRand);

        $this->updateByPrimaryKeyId($groupLeagueAway, $goalRand);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|null $groupLeagueAway
     * @param int $goalRand
     * @return void
     */
    private function updateByPrimaryKeyId(?\Illuminate\Database\Eloquent\Model $groupLeagueAway, int $goalRand): void
    {
        $this->groupLeagueRepository->updateByPrimaryKeyId($groupLeagueAway->id, [
            'total_points' => $groupLeagueAway->total_points + 1,
            'draw' => $groupLeagueAway->draw + 1,
            'match' => $groupLeagueAway->match + 1,
            'goal_for' => ($groupLeagueAway->goal_for + $goalRand),
            'goal_against' => ($groupLeagueAway->goal_against + $goalRand),
            'goal_difference' => ($groupLeagueAway->goal_for + $goalRand) - ($groupLeagueAway->goal_against + $goalRand)
        ]);
    }

    /**
     * @param ClubEntity $club
     * @param string $type
     * @param int $goalFor
     * @param int $goalAgainst
     * @return void
     */
    private function createMatchHistory(
        Club   $club,
        int    $groupId,
        string $matchUuid,
        string $type,
        int    $goalFor,
        int    $goalAgainst
    )
    {
        $leagueChampion = $this->leagueRepository->findChampionLeague();
        $matchHome = $this->matchRepository->findOneWhere([
            'match_uuid' => $matchUuid,
            'club_id' => $club->getId()
        ]);


        $point = $type === MatchHistory::TYPE_MATCH_WIN ? 3 : 0;
        $this->matchHistoryRepository->create([
            'match_id' => $matchHome->id,
            'points' => $point,
            'type' => $type,
            'goal_for' => $goalFor,
            'goal_against' => $goalAgainst,
        ]);

        $groupLeague = $this->groupLeagueRepository->findOneWhere([
            'group_id' => $groupId,
            'club_id' => $club->getId(),
            'league_id' => $leagueChampion->id
        ]);

        $this->groupLeagueRepository->updateByPrimaryKeyId($groupLeague->id, [
            'total_points' => $groupLeague->total_points + $point,
            'match' => $groupLeague->match + 1,
            'win' => $type === MatchHistory::TYPE_MATCH_WIN ? $groupLeague->win + 1 : $groupLeague->win + 0,
            'loose' => $type === MatchHistory::TYPE_MATCH_LOOSE ? $groupLeague->loose + 1 : $groupLeague->loose + 0,
            'goal_for' => ($groupLeague->goal_for + $goalFor),
            'goal_against' => ($groupLeague->goal_against + $goalAgainst),
            'goal_difference' => ($groupLeague->goal_for + $goalFor) - ($groupLeague->goal_against + $goalAgainst)
        ]);
    }

    /**
     * @param int $groupId
     * @return void
     */
    public function reset(int $groupId): void
    {
        $datas = $this->groupMatchRepository->findWhereHas([
            'group_id' => $groupId
        ], ['matchHistory']);

        /** @var int[] $matchIds */
        $matchIds = $datas->pluck('match_id')->toArray();
        $this->matchHistoryRepository->destroyWhereIn('match_id', $matchIds);

        $this->groupLeagueRepository->updateWithConditions(['group_id' => $groupId], [
            'total_points' => 0,
            'match' => 0,
            'win' => 0,
            'draw' => 0,
            'loose' => 0,
            'goal_for' => 0,
            'goal_against' => 0,
            'goal_difference' => 0,
        ]);
    }

    public function getMatchRepository(): MatchRepository
    {
        return $this->matchRepository;
    }

    /**
     * @param int $groupId
     * @return Games[]
     */
    public function getResultMatchByGroupId(int $groupId): array
    {
        $groupMatches = $this->groupMatchRepository->getHistoryClubMatchByGroupId($groupId);

        if (count($groupMatches) === 0) {
            return [];
        }

        /** @var Games[] $result */
        $result = [];

        $dataGroupedByMatchUuid = [];

        /** @var int[] $matchIds */
        $matchIds = [];
        foreach ($groupMatches as $groupMatch) {

            $dataClub = $groupMatch->matchHistory->match->club;
            $matchHistory = $groupMatch->matchHistory;
            $type = $matchHistory->match->type;

            $standing = new Standing();
            $standing->setGoalFor($matchHistory->goal_for);

            $club = new Club();
            $club->setId($dataClub->id);
            $club->setName($dataClub->name);
            $club->setStanding($standing);

            $matchIds[] = $matchHistory->match_id;
            $dataGroupedByMatchUuid[$matchHistory->match->match_uuid][$type]['club'] = $club;
            $dataGroupedByMatchUuid[$matchHistory->match->match_uuid][$type]['goal'] = $matchHistory->goal_for;
        }

        $latestDateMatch = $this->matchRepository->findOneWhereInLatestDateMatch('id', $matchIds)->date_match;
        $latestDateMatch = Carbon::parse($latestDateMatch)->endOfWeek()->startOfDay();

        $firstDateMatch = $this->matchRepository->findOneWhereInFirstDateMatch('id', $matchIds)->date_match;
        $firstDateMatch = Carbon::parse($firstDateMatch)->startOfDay();
        $firstDateMatch = $firstDateMatch->subDays($firstDateMatch->dayOfWeek);

        $totalWeek = $latestDateMatch->copy()->diffInWeeks($firstDateMatch);

        $matchGroupedByWeek = [];
        foreach ($dataGroupedByMatchUuid as $matchUuid => $data) {
            $match = $this->matchRepository->findOneWhere(['match_uuid' => $matchUuid]);
            $clubMatch = new ClubMatch();

            $dataMatch = Carbon::parse($match->date_match);
            $dataMatch = $dataMatch->copy()->subDays($dataMatch->dayOfWeek);

            $currentWeek = $totalWeek - $latestDateMatch->diffInWeeks($dataMatch);

            $clubMatch->setMatchUuid($matchUuid);
            $clubMatch->setHome($data['home']['club']);
            $clubMatch->setAway($data['away']['club']);
            $clubMatch->setDate(Carbon::parse($match->date_match));

            $matchGroupedByWeek[$currentWeek][] = $clubMatch;
        }

        foreach ($matchGroupedByWeek as $week => $clubsMatch) {
            $games = new Games();
            $games->setName("Week " . $week);

            foreach ($clubsMatch as $clubMatch) {
                $games->addClub($clubMatch);
            }

            $result[] = $games;
        }

        return $result;
    }
}
