<?php

namespace App\Repositories;

use App\Interfaces\UserBadgeInterface;
use App\UserBadge;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserBadgeRepository implements UserBadgeInterface
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return UserBadge::query()->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return UserBadge::query()->findOrFail($id);
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        return UserBadge::query()->create($params);
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function update(array $params, int $id): Model
    {
        $product = UserBadge::query()->findOrFail($id);
        $product->update($params);

        return $product;
    }

    /**
     * @param int $id
     * @return null
     */
    public function delete(int $id)
    {
        UserBadge::query()->findOrFail($id)->delete();

        return null;
    }

}