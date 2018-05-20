<?php

namespace App\Repositories;

use App\Interfaces\ProductInterface;
use App\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductRepository implements ProductInterface
{
    private $categoryRepository;

    /**
     * ProductRepository constructor.
     * @param CategoryRepository $cr
     */
    public function __construct(CategoryRepository $cr)
    {
        $this->categoryRepository = $cr;
    }


    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Product::query()->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return Product::query()->with('ingredients.ingredient:id,name,allergenic')->findOrFail($id);
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        return Product::query()->create($params);
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function update(array $params, int $id): Model
    {
        $product = Product::query()->findOrFail($id);
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
        Product::query()->findOrFail($id)->delete();

        return null;
    }

    /**
     * @return array
     */
    public function findAllOrderedByCategory()
    {
        $categories = $this->categoryRepository->findAll();
        $response = [];

        foreach ($categories as $key => $val) {
            $response[$key] = $val;
            $response[$key]['list'] = $this->findAllByCategory($val['id']);
        }

        return $response;
    }

    /**
     * @param int $id
     * @return Collection|static[]
     */
    public function findAllByCategory(int $id)
    {
        return Product::query()->where('categories_id', '=', $id)->get();
    }
}
