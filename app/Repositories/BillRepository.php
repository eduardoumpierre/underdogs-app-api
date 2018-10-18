<?php

namespace App\Repositories;

use App\Interfaces\BillInterface;
use App\Bill;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BillRepository implements BillInterface
{
    private $billProductRepository;
    private $achievementRepository;
    private $userRepository;

    /**
     * BillRepository constructor.
     * @param BillProductRepository $bpr
     * @param AchievementRepository $ar
     * @param UserRepository $ur
     */
    public function __construct(BillProductRepository $bpr, AchievementRepository $ar, UserRepository $ur)
    {
        $this->billProductRepository = $bpr;
        $this->achievementRepository = $ar;
        $this->userRepository = $ur;
    }

    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Bill::query()
            ->from('bills AS b')
            ->select('b.id', 'c.number', 'u.name')
            ->join('cards AS c', 'c.id', '=', 'b.cards_id')
            ->join('users AS u', 'u.id', '=', 'b.users_id')
            ->where('is_active', '=', 1)->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return Bill::query()->findOrFail($id);
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public function findOneByIdWithProducts(int $id)
    {
        $bill = Bill::query()
            ->with(['card', 'user:id,name'])
            ->where('id', '=', $id)
            ->where('is_active', '=', true)
            ->limit(1)
            ->firstOrFail();

        $total = 0;
        $products = $this->billProductRepository->findAllByBill($bill['id']);
        $bill['products'] = $products;

        foreach ($products as $key => $val) {
            $total += $val['price'];
        }

        $bill['total'] = $total;

        return $bill;
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public function findOneByUserIdWithProducts(int $id)
    {
        $bill = Bill::query()
            ->with(['card', 'user:id,name'])
            ->where('users_id', '=', $id)
            ->where('is_active', '=', true)
            ->limit(1)
            ->firstOrFail();

        $total = 0;
        $products = $this->billProductRepository->findAllByBill($bill['id'], null, true);
        $bill['products'] = $products;

        foreach ($products as $key => $val) {
            $total += $val['price'] * $val['quantity'];
        }

        $bill['total'] = $total;

        return $bill;
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        $params['is_active'] = true;

        return Bill::query()->create($params);
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function update(array $params, int $id): Model
    {
        $product = Bill::query()->findOrFail($id);
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
        Bill::query()->findOrFail($id)->delete();

        return null;
    }

    /**
     * @param int $id
     * @return Collection|Model
     */
    public function checkout(int $id)
    {
        $bill = Bill::query()->where('is_active', '=', 1)->findOrFail($id);
        $bill->is_active = 0;
        $bill->update();

        $products = $this->billProductRepository->findAllByBill($id, ['p.experience'])->toArray();

        $experience = 0;

        foreach ($products as $key => $val) {
            $experience += $val['experience'];
        }

        $experience += $this->achievementRepository->updateAchievementsByUserId($bill->users_id);

        return $this->userRepository->addExperience($bill->users_id, $experience);
    }
}
