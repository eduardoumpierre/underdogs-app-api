<?php

namespace App\Repositories;

use App\User;
use App\Interfaces\UserInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository implements UserInterface
{
    private $levelRepository;
    private $userDropRepository;

    /**
     * UserRepository constructor.
     * @param LevelRepository $lr
     * @param UserDropRepository $udr
     */
    public function __construct(LevelRepository $lr, UserDropRepository $udr)
    {
        $this->levelRepository = $lr;
        $this->userDropRepository = $udr;
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
            $user = User::query()->findOrFail($id, $columns);
            $user['current_level'] = $this->levelRepository->findOneById($user->levels_id);
            $user['next_level'] = $this->levelRepository->findOneByNumber($user['current_level']['number'] + 1);

            return $user;
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
        $user->levels_id = $this->updateUserLevel($user->id, $user->levels_id, $user->experience, $experience);
        $user->experience += $experience;
        $user->update();

        return $user;
    }

    /**
     * Returns the new user level
     *
     * @param int $userId
     * @param int $currentLevel
     * @param int $currentExperience
     * @param int $newExperience
     * @return int
     */
    private function updateUserLevel(int $userId, int $currentLevel, int $currentExperience, int $newExperience)
    {
        $levels = $this->levelRepository->findNextLevelsByExperience($currentLevel, $currentExperience, $newExperience);

        if (count($levels) > 0) {
            foreach ($levels as $key => $val) {
                // @todo Adds record to history

                // Adds a new drop from level's drops
                $this->userDropRepository->create(['user' => $userId, 'level' => $val['number']]);
            }

            return $levels->last()->id;
        }

        return $currentLevel;
    }
}
