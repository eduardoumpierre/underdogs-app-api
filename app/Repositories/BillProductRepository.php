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
     * @return bool
     */
    public function insert(array $params)
    {
        $data = [];

        foreach ($params['products'] as $key => $val) {
            $data[$key] = array_merge([
                'bills_id' => $params['bills_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ], $val);
        }

        return BillProduct::query()->insert($data);
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
     * @param array|null $params
     * @param bool $stack
     * @return Collection|static[]
     */
    public function findAllByBill(int $id, array $params = null, bool $stack = false)
    {
        if (!$params) {
            $params = ['p.id', 'p.name', 'p.price'];
        }

        if ($stack) {
            $params[] = DB::raw('COALESCE(COUNT(p.id), 0) as quantity');
        }

        $query = BillProduct::query()
            ->from('bills_products AS bp')
            ->select($params)
            ->join('products AS p', 'p.id', '=', 'bp.products_id')
            ->where('bp.bills_id', '=', $id)
            ->orderByDesc('bp.created_at');

        if ($stack) {
            $query->groupBy('p.id');
        }

        $query = $query->get();

        return $query;
    }

    /**
     * @param int $id
     * @param int $productId
     * @return mixed
     */
    public function deleteOneProductById(int $id, int $productId)
    {
        BillProduct::query()->where('bills_id', '=', $id)->where('products_id', '=', $productId)->delete();

        return BillProduct::query()
            ->from('bills_products AS bp')
            ->select(DB::raw('COALESCE(SUM(p.price), 0) as total'))
            ->join('products AS p', 'p.id', '=', 'bp.products_id')
            ->where('bp.bills_id', '=', $id)
            ->get()->toArray()[0];
    }

    /**
     * @param int $productId
     * @param int $userId
     * @return Model|static
     */
    public function findAllProductConsumeByProductIdAndUserId(int $productId, int $userId)
    {
        return BillProduct::query()
            ->from('bills_products AS bp')
            ->select([DB::raw('COUNT(bp.id) AS count')])
            ->join('bills AS b', 'b.users_id', '=', 'bp.bills_id')
            ->where('bp.products_id', '=', $productId)
            ->where('b.users_id', '=', $userId)
            ->firstOrFail();
    }
}
