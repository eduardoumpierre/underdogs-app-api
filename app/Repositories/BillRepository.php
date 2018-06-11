<?php

namespace App\Repositories;

use App\Interfaces\BillInterface;
use App\Bill;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BillRepository implements BillInterface
{
    private $billProductRepository;

    /**
     * BillRepository constructor.
     * @param BillProductRepository $bpr
     */
    public function __construct(BillProductRepository $bpr)
    {
        $this->billProductRepository = $bpr;
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
     * @return Collection|Model|null|static|static[]
     */
    public function findOneByIdWithProducts(int $id)
    {
        $bill = Bill::query()
            ->with(['card', 'user:id,name'])
            ->where('users_id', '=', $id)
            ->where('is_active', '=', true)
            ->limit(1)
            ->first();

        if ($bill) {
            $total = 0;
            $products = $this->billProductRepository->findAllByBill($bill['id']);
            $bill['products'] = $products;

            foreach ($products as $key => $val) {
                $total += $val['price'] * $val['quantity'];
            }

            $bill['total'] = $total;
        }

        return $bill ? $bill : [];
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

}
