<?php

namespace App\Repositories;

use App\User;
use App\Interfaces\UserInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository implements UserInterface
{
    private $levelRepository;

    /**
     * UserRepository constructor.
     * @param LevelRepository $lr
     */
    public function __construct(LevelRepository $lr)
    {
        $this->levelRepository = $lr;
    }


    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return User::query()->get();
    }

    /**
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function findOneById(int $id, array $columns = ['*']): Model
    {
        if (['*'] === $columns) {
            return User::query()->with('level')->findOrFail($id, $columns);
        }

        return User::query()->findOrFail($id, $columns);
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        return User::query()->create($params);
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function update(array $params, int $id): Model
    {
        $product = User::query()->findOrFail($id);
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
        User::query()->findOrFail($id)->delete();

        return null;
    }

    /**
     * Adds experience to user
     *
     * @param int $id
     * @param int $experience
     * @return Collection|Model
     */
    public function addExperience(int $id, int $experience)
    {
        $user = $this->findOneById($id, ['id', 'experience', 'levels_id']);
        $user->experience += $experience;
        $user->levels_id = $this->updateUserLevel($user->experience, $user->levels_id);
        $user->update();

        return $user;
    }

    /**
     * Returns the new user level
     *
     * @param int $experience
     * @param int $currentLevel
     * @return int|mixed
     */
    private function updateUserLevel(int $experience, int $currentLevel)
    {
        $levels = $this->levelRepository->findNextLevelsByExperience($experience, $currentLevel);

        if (count($levels) > 0) {
            foreach ($levels as $key => $val) {
                // Adds record to history
            }

            return $levels->last()->id;
        }

        return $currentLevel;
    }
}
