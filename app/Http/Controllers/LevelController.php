<?php

namespace App\Http\Controllers;

use App\Repositories\LevelRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LevelController extends Controller
{
    private $levelRepository;

    /**
     * LevelController constructor.
     * @param LevelRepository $lr
     */
    public function __construct(LevelRepository $lr)
    {
        $this->levelRepository = $lr;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->levelRepository->findAll();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getOne(int $id): Model
    {
        return $this->levelRepository->findOneById($id);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'number' => 'required|numeric|unique:levels',
            'experience' => 'required|numeric',
            'drops' => 'required|array'
        ]);

        return response()->json($this->levelRepository->create($request->all()), Response::HTTP_CREATED);
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
            'number' => 'required|numeric|unique:levels,number,' . $id,
            'experience' => 'required|numeric',
            'drops' => 'required|array'
        ]);

        return response()->json($this->levelRepository->update($request->all(), $id));
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(int $id): JsonResponse
    {
        return response()->json($this->levelRepository->delete($id), Response::HTTP_NO_CONTENT);
    }
}
