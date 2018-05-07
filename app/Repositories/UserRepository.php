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
     */
    public function delete(int $id)
    {
        User::query()->findOrFail($id)->delete();

        return null;
    }

    /**
     * @param int $id
     * @param int $experience
     * @return Collection|Model
     */
    public function addExperience(int $id, int $experience)
    {
        $user = $this->findOneById($id, ['id', 'experience']);
        $user->experience += $experience;
        $user->update();

        $this->updateUserLevel($id);

        return $user;
    }

    /**
     * @param int $id
     */
    private function updateUserLevel(int $id)
    {
        $user = $this->findOneById($id, ['id', 'experience', 'levels_id']);
        $nextLevel = $this->levelRepository->findNextLevelById($user->levels_id);

        if ($user->experience >= $nextLevel->experience) {
            $user->levels_id = $nextLevel->id;
            $user->update();
        }

        return;
    }
}
