<?php

namespace App\Repositories;

use App\Interfaces\ProductIngredientInterface;
use App\ProductIngredient;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductIngredientRepository implements ProductIngredientInterface
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return ProductIngredient::query()->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return ProductIngredient::query()->findOrFail($id);
    }

    /**
     * @param int $id
     * @return array
     */
    public function findAllByProductId(int $id) {
        return ProductIngredient::query()
            ->from('products_ingredients AS pi')
            ->select(['i.id', 'i.name', 'i.allergenic'])
            ->join('ingredients AS i', 'i.id', '=', 'pi.ingredients_id')
            ->where('pi.products_id', '=', $id)
            ->get();
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        return ProductIngredient::query()->create($params);
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function update(array $params, int $id): Model
    {
        $product = ProductIngredient::query()->findOrFail($id);
        $product->update($params);

        return $product;
    }

    /**
     * @param int $id
     * @return null
     */
    public function delete(int $id)
    {
        ProductIngredient::query()->findOrFail($id)->delete();

        return null;
    }

    /**
     * @param int $id
     * @param array $ingredients
     * @param bool $removeOld
     * @return bool
     */
    public function insert(int $id, array $ingredients, bool $removeOld = false)
    {
        if ($removeOld) {
            $this->removeAllProducts($id);
        }

        $data = [];

        foreach ($ingredients as $key => $val) {
            $data[$key] = [
                'ingredients_id' => $val,
                'products_id' => $id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        return ProductIngredient::query()->insert($data);
    }

    /**
     * @param int $id
     */
    public function removeAllProducts(int $id) {
        ProductIngredient::query()->where('products_id', '=', $id)->delete();
    }
}
