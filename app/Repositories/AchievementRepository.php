<?php

namespace App\Repositories;

use App\Interfaces\AchievementInterface;
use App\Achievement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AchievementRepository implements AchievementInterface
{
    private $userAchievementRepository;
    private $billProductRepository;
    private $userDropRepository;

    /**
     * AchievementRepository constructor.
     * @param UserAchievementRepository $uar
     * @param BillProductRepository $bpr
     */
    public function __construct(UserAchievementRepository $uar, BillProductRepository $bpr, UserDropRepository $udr)
    {
        $this->userAchievementRepository = $uar;
        $this->billProductRepository = $bpr;
        $this->userDropRepository = $udr;
    }

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

    /**
     * @param int $userId
     * @return int
     */
    public function updateAchievementsByUserId(int $userId)
    {
        $experience = 0;
        $achievements = $this->getAllAchievementsNotCompletedByUserId($userId);

        foreach ($achievements as $key => $val) {
            $userValue = 0;

            if ($val['category'] === 0) {
                $userValue = $this->billProductRepository->findAllProductConsumeByProductIdAndUserId($val['entity'], $userId)['count'];
            } else {
                // @todo Adicionar conquista de interação
            }

            if ($userValue >= $val['value']) {
                $experience += $val['experience'];

                if ($val['drops_id']) {
                    $this->userDropRepository->insertCustomDrop($userId, $val['drops_id']);
                }

                $this->userAchievementRepository->create([
                    'users_id' => $userId,
                    'achievements_id' => $val['id']
                ]);
            }
        }

        return $experience;
    }

    /**
     * @param int $userId
     * @return Collection|\Illuminate\Support\Collection|static[]
     */
    private function getAllAchievementsNotCompletedByUserId(int $userId)
    {
        $subQuery = '(SELECT achievements_id FROM users_achievements WHERE users_id = ?)';

        return Achievement::query()
            ->select(['id', 'experience', 'category', 'entity', 'value', 'drops_id'])
            ->whereRaw('id NOT IN ' . $subQuery)
            ->setBindings([$userId])
            ->get()->toArray();
    }
}
