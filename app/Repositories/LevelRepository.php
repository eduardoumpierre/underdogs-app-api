<?php

namespace App\Repositories;

use App\Interfaces\LevelInterface;
use App\Level;
use App\LevelDrop;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class LevelRepository implements LevelInterface
{
    private $levelDropRepository;

    /**
     * LevelRepository constructor.
     * @param LevelDropRepository $ldr
     */
    public function __construct(LevelDropRepository $ldr)
    {
        $this->levelDropRepository = $ldr;
    }

    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Level::query()->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        $level = Level::query()->findOrFail($id);
        $level['drops'] = $this->levelDropRepository->findAllByLevelId($id);

        return $level;
    }

    /**
     * @param int $number
     * @param array $columns
     * @return Model
     */
    public function findOneByNumber(int $number, array $columns = ['*']): Model
    {
        return Level::query()->where('number', '=', $number)->firstOrFail($columns);
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        $level = Level::query()->create($params);

        $this->levelDropRepository->insert($params['drops'], $level->id);

        return $level;
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function update(array $params, int $id): Model
    {
        $level = Level::query()->findOrFail($id);
        $level->update($params);

        $this->levelDropRepository->insert($params['drops'], $id);

        return $level;
    }

    /**
     * @param int $id
     * @return null
     * @throws \Exception
     */
    public function delete(int $id)
    {
        Level::query()->findOrFail($id)->delete();

        return null;
    }

    /**
     * Returns the next level by id
     *
     * @param int $id
     * @return Model
     */
    public function findNextLevelById(int $id): Model
    {
        $level = $this->findOneById($id);

        return $this->findOneByNumber($level->number + 1);
    }

    /**
     * Returns the upgraded levels
     *
     * @param int $currentLevel
     * @param int $currentExperience
     * @param int $newExperience
     * @return Collection
     */
    public function findNextLevelsByExperience(int $currentLevel, int $currentExperience, int $newExperience): Collection
    {
        $levels = Level::query()
            ->where('experience', '>=', $currentExperience)
            ->where('experience', '<=', $currentExperience + $newExperience)
            ->where('id', '>', $currentLevel)
            ->orderBy('number')
            ->get();

        return $levels;
    }
}
