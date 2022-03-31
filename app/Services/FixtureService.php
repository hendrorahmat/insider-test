<?php

namespace App\Services;

use App\Entities\GroupChampionLeague;
use App\Models\MatchModel;
use App\Repositories\ClubRepository;
use App\Repositories\GroupLeagueRepository;
use App\Repositories\GroupMatchRepository;
use App\Repositories\LeagueRepository;
use App\Repositories\MatchRepository;
use Carbon\Carbon;

class FixtureService
{
    const DAY_OF_WEEK_TUESDAY = 2;

    const DAY_OF_WEEK_WEDNESDAY = 3;

    /**
     * @var int[]
     */
    private $matchIds;

    /**
     * @var MatchRepository
     */
    private $matchRepository;

    /**
     * @var LeagueRepository
     */
    private $leagueRepository;

    /**
     * @var GroupLeagueRepository
     */
    private $groupLeagueRepository;

    /**
     * @var ClubRepository
     */
    private $clubRepository;

    /**
     * @var GroupMatchRepository
     */
    private $groupMatchRepository;

    public function __construct(
        MatchRepository       $matchRepository,
        LeagueRepository      $leagueRepository,
        GroupLeagueRepository $groupLeagueRepository,
        ClubRepository        $clubRepository,
        GroupMatchRepository $groupMatchRepository
    )
    {
        $this->matchRepository = $matchRepository;
        $this->leagueRepository = $leagueRepository;
        $this->groupLeagueRepository = $groupLeagueRepository;
        $this->clubRepository = $clubRepository;
        $this->groupMatchRepository = $groupMatchRepository;
    }

    /**
     * @param GroupChampionLeague $group
     * @return void
     */
    public function generateFixtures(GroupChampionLeague $group): void
    {
        $clubs = $group->getClubs();
        $clubIds = [];
        foreach ($clubs as $club) {
            $clubIds[] = $club->getId();
        }

        $championLeague = $this->leagueRepository->findChampionLeague();
        foreach ($clubIds as $clubId) {
            if (
                $this->matchRepository->isClubAlreadyHasAwayMaxMatch($clubId) &&
                $this->matchRepository->isClubAlreadyHasHomeMaxMatch($clubId)
            ) {
                continue;
            }
            $opponents = $this->groupLeagueRepository->findOpponentClubId(
                $clubId,
                $group->getGroup()->getId(),
                $championLeague->id
            );

            foreach ($opponents as $opponent) {
                $listClubMatches = $this->matchRepository->getMatchListClubId($clubId);

                if (count($listClubMatches) === 0) {
                    $matchUuid = $this->matchRepository->generateMatchUuid();
                    $dateMatch = $this->determineMatchOnTuesdayOrWednesday($clubId);

                    $match = $this->matchRepository->create([
                        'match_uuid' => $matchUuid,
                        'league_id' => $championLeague->id,
                        'club_id' => $clubId,
                        'date_match' => $dateMatch->copy()->endOfDay(),
                        'type' => MatchModel::TYPE_HOME
                    ]);

                    $this->addMatchId($match->id);

                    $match = $this->matchRepository->create([
                        'match_uuid' => $matchUuid,
                        'league_id' => $championLeague->id,
                        'club_id' => $opponent->club_id,
                        'date_match' => $dateMatch->copy()->endOfDay(),
                        'type' => MatchModel::TYPE_AWAY
                    ]);
                    $this->addMatchId($match->id);
                } else {
                    $isExistsOpponent = false;
                    foreach ($listClubMatches as $listClubMatch) {
                        $opponentMatch = $this->matchRepository->findOneWhere([
                            'match_uuid' => $listClubMatch->match_uuid,
                            'club_id' => $opponent->club_id
                        ]);

                        if (!is_null($opponentMatch)) {
                            $isExistsOpponent = true;
                            break;
                        }
                    }

                    if ($isExistsOpponent) {
                        $isClubTypeSwappedExists = $this->matchRepository->findOneWhere([
                            'match_uuid' => $opponentMatch->match_uuid,
                            'club_id' => $clubId,
                            'type' => $opponentMatch->type
                        ]);


                        if (!$isClubTypeSwappedExists) {
                            $dateMatch = $this->generateDateMatch($clubId, $opponent->club_id);
                            $matchUuid = $this->matchRepository->generateMatchUuid();

                            $match = $this->matchRepository->create([
                                'type' => $this->reverseType($opponentMatch->type),
                                'match_uuid' => $matchUuid,
                                'club_id' => $opponentMatch->club_id,
                                'league_id' => $championLeague->id,
                                'date_match' => $dateMatch->copy()->endOfDay()
                            ]);
                            $this->addMatchId($match->id);

                            $match = $this->matchRepository->create([
                                'type' => $opponentMatch->type,
                                'match_uuid' => $matchUuid,
                                'club_id' => $clubId,
                                'league_id' => $championLeague->id,
                                'date_match' => $dateMatch->copy()->endOfDay()
                            ]);
                            $this->addMatchId($match->id);
                        }
                    } else {
                        $matchUuid = $this->matchRepository->generateMatchUuid();
                        $dateMatch = $this->generateDateMatch($clubId, $opponent->club_id);

                        $match = $this->matchRepository->create([
                            'match_uuid' => $matchUuid,
                            'league_id' => $championLeague->id,
                            'club_id' => $clubId,
                            'date_match' => $dateMatch->copy()->endOfDay(),
                            'type' => MatchModel::TYPE_HOME
                        ]);
                        $this->addMatchId($match->id);

                       $match = $this->matchRepository->create([
                            'match_uuid' => $matchUuid,
                            'league_id' => $championLeague->id,
                            'club_id' => $opponent->club_id,
                            'date_match' => $dateMatch->copy()->endOfDay(),
                            'type' => MatchModel::TYPE_AWAY
                        ]);
                        $this->addMatchId($match->id);
                    }
                }
            }
        }

        $dataCreateGroupMatch = [];
        foreach ($this->getMatchIds() as $matchId) {
            $dataCreateGroupMatch[] = [
              'group_id' => $group->getGroup()->getId(),
              'match_id' => $matchId,
            ];
        }
        $this->groupMatchRepository->createBulk($dataCreateGroupMatch);
    }

    public function determineMatchOnTuesdayOrWednesday(int $clubId): Carbon
    {
        $tuesday = $this->generateTuesdayDate();
        $wednesday = $this->generateWednesdayDate();
        do {

            $isMatchTuesdayExists = $this->matchRepository
                ->isDateMatchAlreadyExistsSpecificClubId(
                    $tuesday->copy()->endOfWeek()->subDays(7),
                    $tuesday->copy()->endOfWeek()->endOfDay(),
                    $clubId
                );

            $isMatchWednesdayExists = $this->matchRepository
                ->isDateMatchAlreadyExistsSpecificClubId(
                    $wednesday->copy()->endOfWeek()->subDays(7),
                    $wednesday->copy()->endOfWeek()->endOfDay(),
                    $clubId
                );

            if ($isMatchTuesdayExists && $isMatchWednesdayExists) {
                $tuesday = $tuesday->copy()->addWeek();
                $wednesday = $wednesday->copy()->addWeek();
            }

            if (!$isMatchTuesdayExists) {
                return $tuesday->endOfDay();
            }

            if (!$isMatchWednesdayExists) {
                return $wednesday->endOfDay();
            }

        } while (true);
    }

    private function generateTuesdayDate(): Carbon
    {
        $now = Carbon::now();

        if ($now->dayOfWeek < FixtureService::DAY_OF_WEEK_TUESDAY) {
            $tuesdayDateOfWeek = FixtureService::DAY_OF_WEEK_TUESDAY - $now->dayOfWeek;
            $tuesdayDate = Carbon::now()->addDays($tuesdayDateOfWeek);
        } else {
            $tuesdayDateOfWeek = $now->dayOfWeek - FixtureService::DAY_OF_WEEK_TUESDAY;
            $tuesdayDate = $now->subDays($tuesdayDateOfWeek);
        }
        return $tuesdayDate;
    }

    private function generateWednesdayDate(): Carbon
    {
        $now = Carbon::now();

        if ($now->dayOfWeek < FixtureService::DAY_OF_WEEK_WEDNESDAY) {
            $tuesdayDateOfWeek = FixtureService::DAY_OF_WEEK_WEDNESDAY - $now->dayOfWeek;
            $wednesdayDate = Carbon::now()->addDays($tuesdayDateOfWeek);
        } else {
            $tuesdayDateOfWeek = $now->dayOfWeek - FixtureService::DAY_OF_WEEK_WEDNESDAY;
            $wednesdayDate = $now->subDays($tuesdayDateOfWeek);
        }
        return $wednesdayDate;
    }

    public function generateDateMatch(int $clubIdOne, int $clubIdTwo): Carbon
    {
        $dateMatch = $this->determineMatchOnTuesdayOrWednesday($clubIdOne);

        do {
            $isAvailable = $this->matchRepository->isDateAvailableByClubIdOneAndTwo([
                $dateMatch->copy()->endOfWeek()->subDays(7), $dateMatch->copy()->endOfWeek()->endOfDay()
            ], $clubIdOne, $clubIdTwo);

            if ($isAvailable) {
                if ($dateMatch->dayOfWeek === FixtureService::DAY_OF_WEEK_TUESDAY) {
                    $countryIds = $this->clubRepository->getCountryFromClubIds([$clubIdOne, $clubIdTwo]);

                    $isMatchSameCountryExists = $this->matchRepository->isThereAnyClubMatchWithSpecificDateInSameNation(
                        $dateMatch->copy()->startOfDay(),
                        $dateMatch->copy()->endOfDay(),
                        $countryIds
                    );

                    if ($isMatchSameCountryExists) {
                        return $dateMatch->copy()->addDay();
                    }
                }
                return $dateMatch;
            }

            if ($dateMatch->dayOfWeek >= FixtureService::DAY_OF_WEEK_WEDNESDAY) {
                $dateMatch = $dateMatch->subDay()->addWeek()->copy();
            } else {
                $dateMatch = $dateMatch->addDay()->copy();
            }

        } while (true);
    }

    /**
     * @param string $type
     * @return string
     */
    private function reverseType(string $type): string
    {
        return $type === MatchModel::TYPE_HOME ? MatchModel::TYPE_AWAY : MatchModel::TYPE_HOME;
    }

    /**
     * @param array $clubIds
     * @return int
     */
    public function findRandomClub(array $clubIds): int
    {
        return rand(1, count($clubIds) - 1);
    }

    private function removeCLubIfHasMaxMatch(int $clubId, array $clubIds)
    {
        $isClubAlreadyHasHomeMaxMatch = $this->matchRepository->isClubAlreadyHasHomeMaxMatch($clubId);
        $isClubAlreadyHasAwayMaxMatch = $this->matchRepository->isClubAlreadyHasAwayMaxMatch($clubId);

        if ($isClubAlreadyHasHomeMaxMatch && $isClubAlreadyHasAwayMaxMatch) {
            foreach ($clubIds as $key => $id) {
                if ($id === $clubId) {
                    unset($clubIds[$key]);
                }
            }
        }
        return $clubIds;
    }

    /**
     * @param int $matchId
     * @return $this
     */
    public function addMatchId(int $matchId)
    {
        $this->matchIds[] = $matchId;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getMatchIds(): array
    {
        return $this->matchIds ?? [];
    }
}
