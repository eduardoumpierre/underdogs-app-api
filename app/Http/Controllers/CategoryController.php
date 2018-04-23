<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    private $categoryRepository;

    /**
     * CategoryController constructor.
     * @param CategoryRepository $cr
     */
    public function __construct(CategoryRepository $cr)
    {
        $this->categoryRepository = $cr;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->categoryRepository->findAll();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getOne(int $id): Model
    {
        return $this->categoryRepository->findOneById($id);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        return response()->json($this->categoryRepository->create($request->all()), Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        return response()->json($this->categoryRepository->update($request->all(), $id));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        return response()->json($this->categoryRepository->delete($id), Response::HTTP_NO_CONTENT);
    }
}
