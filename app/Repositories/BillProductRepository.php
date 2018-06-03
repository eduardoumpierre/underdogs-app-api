<?php

namespace App\Repositories;

use App\BillProduct;
use App\Interfaces\BillProductInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    /**
     * @param int $id
     * @return Collection|static[]
     */
    public function findAllByBill(int $id)
    {
        $query = BillProduct::query()
            ->from('bills_products AS bp')
            ->select(['p.id', 'p.name', 'p.price', DB::raw('COALESCE(COUNT(p.id), 0) as quantity')])
            ->join('products AS p', 'p.id', '=', 'bp.products_id')
            ->where('bp.bills_id', '=', $id)
            ->groupBy('p.id')
            ->get();

        return $query;
    }

    /**
     * @param int $id
     * @param int $productId
     * @return null
     */
    public function deleteAllProductsById(int $id, int $productId)
    {
        BillProduct::query()->where('bills_id', '=', $id)->where('products_id', '=', $productId)->delete();

        return null;
    }
}
