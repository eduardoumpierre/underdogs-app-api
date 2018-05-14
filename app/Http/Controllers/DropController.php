<?php

namespace App\Http\Controllers;

use App\Repositories\DropRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DropController extends Controller
{
    private $dropRepository;

    /**
     * DropRepository constructor.
     * @param DropRepository $dr
     */
    public function __construct(DropRepository $dr)
    {
        $this->dropRepository = $dr;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->dropRepository->findAll();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getOne(int $id): Model
    {
        return $this->dropRepository->findOneById($id);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'description' => 'required'
        ]);

        return response()->json($this->dropRepository->create($request->all()), Response::HTTP_CREATED);
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
            'description' => 'required'
        ]);

        return response()->json($this->dropRepository->update($request->all(), $id));
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(int $id): JsonResponse
    {
        return response()->json($this->dropRepository->delete($id), Response::HTTP_NO_CONTENT);
    }
}
