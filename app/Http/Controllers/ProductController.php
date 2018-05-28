<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    private $productRepository;

    /**
     * ProductController constructor.
     * @param ProductRepository $pr
     */
    public function __construct(ProductRepository $pr)
    {
        $this->productRepository = $pr;
    }

    /**
     * @return array|Collection
     */
    public function getAll()
    {
        if (true) {
            return $this->getAllOrderedByCategory();
        }

        return $this->productRepository->findAll();
    }

    /**
     * @return array
     */
    public function getAllOrderedByCategory(): array
    {
        return $this->productRepository->findAllOrderedByCategory();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getOne(int $id): Model
    {
        return $this->productRepository->findOneById($id);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|regex:/^[0-9](\.?[0-9]+)*(\,[0-9]+)$/',
            'experience' => 'required|numeric',
            'categories_id' => 'required|numeric'
        ]);

        return response()->json($this->productRepository->create($request->all()), Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|regex:/^[0-9](\.?[0-9]+)*(\,[0-9]+)$/',
            'experience' => 'required|numeric',
            'categories_id' => 'required|numeric'
        ]);

        return response()->json($this->productRepository->update($request->all(), $id));
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(int $id): JsonResponse
    {
        return response()->json($this->productRepository->delete($id), Response::HTTP_NO_CONTENT);
    }
}
