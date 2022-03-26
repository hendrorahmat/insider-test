<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface PotsRepository
{
    public function findAll(): Collection;
}
