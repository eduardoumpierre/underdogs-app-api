<?php

namespace App\Repositories;

use App\Interfaces\CategoryInterface;
use App\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CategoryRepository implements CategoryInterface
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Category::query()->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return Category::query()->findOrFail($id);
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        return Category::query()->create($params);
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function update(array $params, int $id): Model
    {
        $product = Category::query()->findOrFail($id);
        $product->update($params);

        return $product;
    }

    /**
     * @param int $id
     * @return null
     */
    public function delete(int $id)
    {
        Category::query()->findOrFail($id)->delete();

        return null;
    }

}