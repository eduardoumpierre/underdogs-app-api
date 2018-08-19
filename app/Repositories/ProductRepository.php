<?php

namespace App\Repositories;

use App\Interfaces\ProductInterface;
use App\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductRepository implements ProductInterface
{
    private $categoryRepository;
    private $productIngredientRepository;

    /**
     * ProductRepository constructor.
     * @param CategoryRepository $cr
     * @param ProductIngredientRepository $pir
     */
    public function __construct(CategoryRepository $cr, ProductIngredientRepository $pir)
    {
        $this->categoryRepository = $cr;
        $this->productIngredientRepository = $pir;
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
     * @return Collection|Model
     */
    public function findOneById(int $id): Model
    {
        $product = Product::query()->findOrFail($id);
        $product['ingredients'] = $this->productIngredientRepository->findAllByProductId($id);

        return $product;
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        $product = Product::query()->create($params);

        $this->productIngredientRepository->insert($product->id, $params['ingredients']);

        return $product;
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

        $this->productIngredientRepository->insert($product->id, $params['ingredients'], true);

        return $product;
    }

    /**
     * @param int $id
     * @return null
     * @throws \Exception
     */
    public function delete(int $id)
    {
        $this->productIngredientRepository->removeAllProducts($id);

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
        $products = Product::query()->where('categories_id', '=', $id)->get();

        foreach ($products as $key => $val) {
            $products[$key]['ingredients'] = $this->productIngredientRepository->findAllByProductId($val->id);
        }

        return $products;
    }
}
