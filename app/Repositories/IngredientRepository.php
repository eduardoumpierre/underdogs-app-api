<?php

namespace App\Repositories;

use App\Interfaces\IngredientInterface;
use App\Ingredient;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class IngredientRepository implements IngredientInterface
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Ingredient::query()->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return Ingredient::query()->findOrFail($id);
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        return Ingredient::query()->create($params);
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function update(array $params, int $id): Model
    {
        $product = Ingredient::query()->findOrFail($id);
        $product->update($params);

        return $product;
    }

    /**
     * @param int $id
     * @return null
     */
    public function delete(int $id)
    {
        Ingredient::query()->findOrFail($id)->delete();

        return null;
    }

}