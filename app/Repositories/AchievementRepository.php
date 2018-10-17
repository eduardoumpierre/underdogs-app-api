<?php

namespace App\Repositories;

use App\Interfaces\AchievementInterface;
use App\Achievement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AchievementRepository implements AchievementInterface
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Achievement::query()->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return Achievement::query()->findOrFail($id);
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        return Achievement::query()->create($params);
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function update(array $params, int $id): Model
    {
        $achievement = Achievement::query()->findOrFail($id);
        $achievement->update($params);

        return $achievement;
    }

    /**
     * @param int $id
     * @return null
     * @throws \Exception
     */
    public function delete(int $id)
    {
        Achievement::query()->findOrFail($id)->delete();

        return null;
    }

}
