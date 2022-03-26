<?php

namespace App\Repositories\Eloquent;

use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @return Model
     */
    abstract public function getModel(): Model;

    /**
    * @param array $attributes
    *
    * @return Model
    */
    public function firstOrCreate(array $attributes): Model
    {
        return $this->getModel()->firstOrCreate($attributes);
    }

    /**
    * @param int $id
    * @return Model
    */
    public function find(int $id): ?Model
    {
        return $this->getModel()->find($id);
    }

    public function updateByPrimaryKeyId(int $id, array $data): void
    {
        $this->find($id)->update($data);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->getModel()->all();
    }

    public function randomOne(): Model
    {
        $this->getModel()->inRandomOrder()->first();
    }

    public function findOneWhere(array $conditions): ?Model
    {
        return $this->getModel()->where($conditions)->first();
    }

    public function count(array $conditions = []): int
    {
        return $this->getModel()->where($conditions)->count();
    }
}
