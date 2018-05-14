<?php

namespace App\Repositories;

use App\BillProduct;
use App\Interfaces\BillProductInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BillProductRepository implements BillProductInterface
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return BillProduct::query()->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return BillProduct::query()->findOrFail($id);
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        return BillProduct::query()->create($params);
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function update(array $params, int $id): Model
    {
        $product = BillProduct::query()->findOrFail($id);
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
        BillProduct::query()->findOrFail($id)->delete();

        return null;
    }

}
