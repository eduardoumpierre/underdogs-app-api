<?php

namespace App\Http\Controllers;

use App\Repositories\CardRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CardController extends Controller
{
    private $cardRepository;

    /**
     * BadgeRepository constructor.
     * @param CardRepository $cr
     */
    public function __construct(CardRepository $cr)
    {
        $this->cardRepository = $cr;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->cardRepository->findAll();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getOne(int $id): Model
    {
        return $this->cardRepository->findOneById($id);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'number' => 'required|numeric',
        ]);

        return response()->json($this->cardRepository->create($request->all()), Response::HTTP_CREATED);
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
            'number' => 'required|numeric',
        ]);

        return response()->json($this->cardRepository->update($request->all(), $id));
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(int $id): JsonResponse
    {
        return response()->json($this->cardRepository->delete($id), Response::HTTP_NO_CONTENT);
    }
}
