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

}