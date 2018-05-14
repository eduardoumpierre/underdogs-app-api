<?php

namespace App\Repositories;

use App\Interfaces\UserDropInterface;
use App\UserDrop;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserDropRepository implements UserDropInterface
{
    private $levelDropRepository;

    /**
     * UserDropRepository constructor.
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
        return UserDrop::query()->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return UserDrop::query()->findOrFail($id);
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        $user = $params['user'];
        $level = $params['level'];

        $userDrop = UserDrop::query()->create([
            'users_id' => $user,
            'levels_drops_id' => $this->levelDropRepository->findOneRandomByLevelId($level)['id']
        ]);

        return $userDrop;
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function update(array $params, int $id): Model
    {
        $product = UserDrop::query()->findOrFail($id);
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
        UserDrop::query()->findOrFail($id)->delete();

        return null;
    }

}
