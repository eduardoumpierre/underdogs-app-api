<?php

namespace App\Repositories;

use App\Interfaces\UserAchievementInterface;
use App\UserAchievement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserAchievementRepository implements UserAchievementInterface
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return UserAchievement::query()->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return UserAchievement::query()->findOrFail($id);
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        return UserAchievement::query()->create($params);
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function update(array $params, int $id): Model
    {
        $product = UserAchievement::query()->findOrFail($id);
        $product->update($params);

        return $product;
    }

    /**
     * @param int $id
     * @return null
     * @throws \Exception
     */
    public function delete(int $id)
    {
        UserAchievement::query()->findOrFail($id)->delete();

        return null;
    }

}
