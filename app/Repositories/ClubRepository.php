<?php

namespace App\Repositories;

interface ClubRepository extends BaseRepositoryInterface
{
    /**
     * @param array $clubIds
     * @return int[]
     */
    public function getCountryFromClubIds(array $clubIds): array;
}
