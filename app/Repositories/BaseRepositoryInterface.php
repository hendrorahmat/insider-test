<?php

namespace App\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface EloquentRepositoryInterface
 * @package App\Repositories
 */
interface BaseRepositoryInterface
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function firstOrCreate(array $attributes): Model;

    /**
     * @param int $id
     * @return Model
     */
    public function find(int $id): ?Model;

    /**
     * @param int $id
     * @return Model
     * @throws Exception
     */
    public function findOrFail(int $id): Model;

    /**
     * @param int $id
     * @param array $data
     * @void
     */
    public function updateByPrimaryKeyId(int $id, array $data): void;

    /**
     * @return Collection
     */
    public function all(): Collection;

    /**
     * @return Model
     */
    public function randomOne(): Model;

    /**
     * @param array $conditions
     * @return Model | null
     */
    public function findOneWhere(array $conditions): ?Model;

    /**
     * @param array $conditions
     * @return int
     */
    public function count(array $conditions = []): int;

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * @param array $conditions
     * @return Model
     */
    public function findOneWhereLatestId(array $conditions): ?Model;

    /**
     * @param array $conditions
     * @return Model
     */
    public function findOneWhereLatestTimestamps(array $conditions): ?Model;

    /**
     * @param array $conditions
     * @return Collection
     */
    public function findWhere(array $conditions): Collection;

    /**
     * @param array $conditions
     * @param array $relations
     * @return Collection
     */
    public function findWhereHas(array $conditions, array $relations): Collection;

    /**
     * @param array $data
     * @return void
     */
    public function createBulk(array $data): void;

    /**
     * @param string $column
     * @param array $values
     * @return void
     */
    public function destroyWhereIn(string $column, array $values): void;

    /**
     * @param array $conditions
     * @param array $values
     * @return void
     */
    public function updateWithConditions(array $conditions, array $values): void;

    /**
     * @param string $column
     * @param array $values
     * @return Collection
     */
    public function findWhereIn(string $column, array $values): Collection;
}
