<?php

namespace App\Entities;

class Club
{
    /**
     * @var int $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    private $strength;

    /**
     * @var Standing
     */
    private $standing;

    /**
     * @return Standing
     */
    public function getStanding(): Standing
    {
        return $this->standing;
    }

    /**
     * @param Standing $standing
     * @return Club
     */
    public function setStanding(Standing $standing): Club
    {
        $this->standing = $standing;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Club
     */
    public function setId(int $id): Club
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Club
     */
    public function setName(string $name): Club
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * @param mixed $strength
     * @return Club
     */
    public function setStrength($strength)
    {
        $this->strength = $strength;
        return $this;
    }
}
