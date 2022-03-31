<?php

namespace App\Repositories\Eloquent;

use App\Entities\Club as ClubEntity;
use App\Entities\ClubMatch;
use App\Entities\Games;
use App\Models\MatchModel;
use App\Repositories\MatchRepository;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class EloquentMatchRepository extends BaseRepository implements MatchRepository
{
    /**
     * @return string
     */
    public function generateMatchUuid(): string
    {
        $matchUuid = Uuid::uuid4();
        $isExists = $this->getModel()->where('match_uuid', $matchUuid)->first();

        if (is_null($isExists)) {
            return $matchUuid;
        }
        return $this->generateMatchUuid();
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return new MatchModel();
    }

    /**
     * @param DateTime $start
     * @param DateTime $end
     * @return bool
     */
    public function isDateMatchAlreadyExistsSpecificClubId(DateTime $start, DateTime $end, int $clubId): bool
    {
        return $this->getModel()->whereBetween('date_match', [
                $start,
                $end
            ])->where('club_id', $clubId)->groupBy('match_uuid')->count() > 0;
    }

    /**
     * @param int $clubId
     * @return bool
     */
    public function isClubAlreadyHasHomeMaxMatch(int $clubId): bool
    {
        return $this->getModel()->where('club_id', $clubId)
                ->where('type', MatchModel::TYPE_HOME)->count() >= MatchModel::MAX_HOME_MATCH;
    }

    /**
     * @param int $clubId
     * @return bool
     */
    public function isClubAlreadyHasAwayMaxMatch(int $clubId): bool
    {
        return $this->getModel()->where('club_id', $clubId)
                ->where('type', MatchModel::TYPE_AWAY)->count() >= MatchModel::MAX_AWAY_MATCH;
    }

    /**
     * @param int $clubId
     * @return Collection
     */
    public function getMatchListClubId(int $clubId): Collection
    {
        return $this->getModel()->where('club_id', $clubId)->get();
    }

    public function isDateAvailableByClubIdOneAndTwo(array $dates, int $clubIdOne, int $clubIdTwo): bool
    {
        $data = $this->getModel()
                ->where(function ($query) use ($clubIdOne, $clubIdTwo) {
                    $query->where('club_id', $clubIdOne)->orWhere('club_id', $clubIdTwo);
                })
                ->whereBetween('date_match', $dates)
                ->groupBy('match_uuid')
                ->count() === 0;
        return $data;
    }

    /**
     * @param DateTime $start
     * @param DateTime $end
     * @param int[] $countryIds
     * @return bool
     */
    public function isThereAnyClubMatchWithSpecificDateInSameNation(DateTime $start, DateTime $end, array $countryIds): bool
    {
        return $this->getModel()->whereBetween('date_match', [$start, $end])->whereHas('club', function ($query) use ($countryIds) {
                $query->whereIn('nation_id', $countryIds);
            })->count() > 0;
    }

    /**
     * @param array $clubIds
     * @return Model
     */
    public function findOneWhereFirstDateMatchClub(array $clubIds): Model
    {
        return $this->getModel()->whereIn('club_id', $clubIds)->orderBy('date_match')->first();
    }

    /**
     * @param DateTime[] $dates
     * @param int[] $clubIds
     * @return Collection
     */
    public function findSpecificDateClubIds(array $dates, array $clubIds): Collection
    {
        return $this->getModel()->with('club')
            ->whereIn('club_id', $clubIds)
            ->whereBetween('date_match', $dates)->get();
    }

    /**
     * @param array $clubIds
     * @return Games
     */
    public function findCurrentMatchClubIds(array $clubIds): Games
    {
        $games = new Games();
        $data = $this->getModel()->with('club')
            ->doesntHave('matchHistory')
            ->whereIn('club_id', $clubIds)
            ->orderBy('date_match')
            ->first();

        if (is_null($data)) {
            return $games;
        }

        $lastMatch = $this->findOneWhereLatestDateMatchClub($clubIds);
        $lastMatchDate = Carbon::parse($lastMatch->date_match)->endOfWeek();
        $dateMatch = Carbon::parse($data->date_match)->startOfDay();

        $matchesCurrentWeek = $this->getModel()
                ->with('club')
                ->whereIn('club_id', $clubIds)
                ->whereBetween('date_match', [
                    $dateMatch->copy()->subDays($dateMatch->dayOfWeek),
                    $dateMatch->copy()->endOfWeek()
                ])->get();

        $clubMatch = new ClubMatch();
        foreach ($matchesCurrentWeek as $item) {

            $clubEntity = new ClubEntity();
            $clubEntity->setName($item->club->name);
            $clubEntity->setId($item->club->id);

            if ($item->type === MatchModel::TYPE_HOME) {
                $clubMatch->setHome($clubEntity);
            } else {
                $clubMatch->setAway($clubEntity);
            }

            $clubMatch->setMatchUuid($item->match_uuid);
            $clubMatch->setDate(Carbon::parse($item->date_match));

            if ($clubMatch->getAway() && $clubMatch->getHome()) {
                $games->addClub($clubMatch);
                $clubMatch = new ClubMatch();
            }
        }

        $games->setName("Week " . (MatchModel::TOTAL_MATCH_WEEK - $dateMatch->diffInWeeks($lastMatchDate)));
        return $games;

    }

    /**
     * @param int[] $clubIds
     * @return Model
     */
    public function findOneWhereLatestDateMatchClub(array $clubIds): Model
    {
        return $this->getModel()->whereIn('club_id', $clubIds)->latest('date_match')->first();
    }

    /**
     * @param string $matchUuid
     * @return Collection
     */
    public function findByMatchUuid(string $matchUuid): Collection
    {
        return $this->getModel()->with('club')->where('match_uuid', $matchUuid)->get();
    }

    /**
     * @param array $matchUuids
     * @return Collection
     */
    public function getMatchByUuids(array $matchUuids): Collection
    {
        return $this->getModel()->with('club')->whereIn('match_uuid', $matchUuids)->get();
    }

    /**
     * @param string $distinctColumn
     * @param string $column
     * @param array $values
     * @return Collection
     */
    public function findWhereInDistinct(string $distinctColumn, string $column, array $values): Collection
    {
        return $this->getModel()->select($distinctColumn)->whereIn($column, $values)->distinct()->get();
    }

    /**
     * @param string $column
     * @param array $values
     * @return Model
     */
    public function findOneWhereInLatestDateMatch(string $column, array $values): Model
    {
        return $this->getModel()->whereIn($column, $values)->latest('date_match')->first();
    }

    /**
     * @param string $column
     * @param array $values
     * @return Model
     */
    public function findOneWhereInFirstDateMatch(string $column, array $values): Model
    {
        return $this->getModel()->whereIn($column, $values)->orderBy('date_match')->first();
    }
}
