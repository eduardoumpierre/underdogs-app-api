<?php

namespace App\Repositories;

use App\Interfaces\BillInterface;
use App\Bill;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BillRepository implements BillInterface
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Bill::query()->get();
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
        $bill = Bill::with(['products', 'products.product:id,name,price'])
            ->where('is_active', '=', 1)
            ->find($id);

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
