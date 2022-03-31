<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
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
     * @return Model
     */
    abstract public function getModel(): Model;

    public function findOrFail(int $id): Model
    {
        return $this->getModel()->findOrFail($id);
    }

    public function updateByPrimaryKeyId(int $id, array $data): void
    {
        $this->find($id)->update($data);
    }

    /**
     * @param int $id
     * @return Model
     */
    public function find(int $id): ?Model
    {
        return $this->getModel()->find($id);
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

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->getModel()->create($data);
    }

    /**
     * @param array $conditions
     * @return Model|null
     */
    public function findOneWhereLatestId(array $conditions): ?Model
    {
        return $this->getModel()->where($conditions)->latest('id')->first();
    }

    /**
     * @param array $conditions
     * @return Model|null
     */
    public function findOneWhereLatestTimestamps(array $conditions): ?Model
    {
        return $this->getModel()->where($conditions)->latest()->first();
    }

    /**
     * @param array $conditions
     * @return Collection
     */
    public function findWhere(array $conditions): Collection
    {
        return $this->getModel()->where($conditions)->get();
    }

    public function findWhereHas(array $conditions, array $relations): Collection
    {
        $model = $this->getModel();
        foreach ($relations as $relation) {
            $model = $model->whereHas($relation);
        }
        return $model->where($conditions)->get();
    }

    /**
     * @param array $data
     * @return void
     */
    public function createBulk(array $datas): void
    {
        foreach ($datas as $index => $data) {
            $datas[$index]['created_at'] = Carbon::now();
            $datas[$index]['updated_at'] = Carbon::now();
        }
        $this->getModel()->insert($datas);
    }

    /**
     * @param string $column
     * @param array $values
     * @return void
     */
    public function destroyWhereIn(string $column, array $values): void
    {
        $this->getModel()->whereIn($column, $values)->delete();
    }

    /**
     * @param array $conditions
     * @param array $values
     * @return void
     */
    public function updateWithConditions(array $conditions, array $values): void
    {
        $this->getModel()->where($conditions)->update($values);
    }

    /**
     * @param string $column
     * @param array $values
     * @return Collection
     */
    public function findWhereIn(string $column, array $values): Collection
    {
        return $this->getModel()->whereIn($column, $values);
    }
}
