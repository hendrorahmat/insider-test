<?php

namespace App\Services\Contracts;

use App\Entities\Club;

interface PredictionGameRule
{
    /**
     * @return Club|null
     */
    public function getClubWinner(): ?Club;

    /**
     * @return int
     */
    public function getPoint(): int;
}
