<?php

namespace App\Repositories;

use App\Entities\Games;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface MatchRepository extends BaseRepositoryInterface
{
    /**
     * @return string
     */
    public function generateMatchUuid(): string;

    /**
     * @param DateTime $start
     * @param DateTime $end
     * @param int $clubId
     * @return bool
     */
    public function isDateMatchAlreadyExistsSpecificClubId(DateTime $start, DateTime $end, int $clubId): bool;

    /**
     * @param array $dates
     * @param int $clubIdOne
     * @param int $clubIdTwo
     * @return bool
     */
    public function isDateAvailableByClubIdOneAndTwo(array $dates, int $clubIdOne, int $clubIdTwo): bool;

    /**
     * @param int $clubId
     * @return bool
     */
    public function isClubAlreadyHasHomeMaxMatch(int $clubId): bool;

    /**
     * @param int $clubId
     * @return bool
     */
    public function isClubAlreadyHasAwayMaxMatch(int $clubId): bool;

    /**
     * @param int $clubId
     * @return Collection
     */
    public function getMatchListClubId(int $clubId): Collection;

    /**
     * @param DateTime $start
     * @param DateTime $end
     * @param int[] $countryIds
     * @return bool
     */
    public function isThereAnyClubMatchWithSpecificDateInSameNation(DateTime $start, DateTime $end, array $countryIds): bool;

    /**
     * @param int[] $clubIds
     * @return Model
     */
    public function findOneWhereLatestDateMatchClub(array $clubIds): Model;

    /**
     * @param int[] $clubIds
     * @return Model
     */
    public function findOneWhereFirstDateMatchClub(array $clubIds): Model;

    /**
     * @param DateTime[] $dates
     * @param int[] $clubIds
     * @return Collection
     */
    public function findSpecificDateClubIds(array $dates, array $clubIds): Collection;

    /**
     * @param int[] $clubIds
     * @return Games
     */
    public function findCurrentMatchClubIds(array $clubIds): Games;

    /**
     * @param string $matchUuid
     * @return Collection
     */
    public function findByMatchUuid(string $matchUuid): Collection;

    /**
     * @param int[] $matchUuids
     * @return Collection
     */
    public function getMatchByUuids(array $matchUuids): Collection;

    /**
     * @param string $distinctColumn
     * @param string $column
     * @param array $values
     * @return Collection
     */
    public function findWhereInDistinct(string $distinctColumn, string $column, array $values): Collection;

    /**
     * @param string $column
     * @param array $values
     * @return Model
     */
    public function findOneWhereInLatestDateMatch(string $column, array $values): Model;

    /**
     * @param string $column
     * @param array $values
     * @return Model
     */
    public function findOneWhereInFirstDateMatch(string $column, array $values): Model;
}
