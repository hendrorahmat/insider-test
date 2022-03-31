<?php

namespace App\Entities;

class Games
{
    /**
     * @var string $name
     */
    private $name;

    /**
     * @var ClubMatch[] $clubs
     */
    private $clubs = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Games
     */
    public function setName(string $name): Games
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return ClubMatch[]
     */
    public function getClubs(): array
    {
        return $this->clubs ?? [];
    }

    /**
     * @param ClubMatch $club
     * @return Games
     */
    public function addClub(ClubMatch $club): Games
    {
        $this->clubs[] = $club;
        return $this;
    }
}
