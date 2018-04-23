<?php

namespace App\Repositories;

use App\Interfaces\BadgeInterface;
use App\Badge;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BadgeRepository implements BadgeInterface
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Badge::query()->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return Badge::query()->findOrFail($id);
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        return Badge::query()->create($params);
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function update(array $params, int $id): Model
    {
        $product = Badge::query()->findOrFail($id);
        $product->update($params);

        return $product;
    }

    /**
     * @param int $id
     * @return null
     */
    public function delete(int $id)
    {
        Badge::query()->findOrFail($id)->delete();

        return null;
    }

}