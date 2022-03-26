<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
* Interface EloquentRepositoryInterface
* @package App\Repositories
*/
interface EloquentRepositoryInterface
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
}
