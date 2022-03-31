<?php

namespace App\Entities;

use Exception;

class GroupChampionLeague
{
    /**
     * @var Club[] $clubs
     */
    private $clubs = [];

    /**
     * @var Group
     */
    private $group;

    /**
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * @param Group $group
     * @return GroupChampionLeague
     */
    public function setGroup(Group $group): GroupChampionLeague
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return Club[]
     */
    public function getClubs(): array
    {
        return $this->clubs;
    }

    /**
     * @param Club $club
     * @return $this
     * @throws Exception
     */
    public function addClub(Club $club): GroupChampionLeague
    {
        foreach ($this->clubs as $c) {
            if ($c->getId() === $club->getId()) {
                throw new Exception('Club already added');
            }
        }
        $this->clubs[] = $club;
        return $this;
    }
}
