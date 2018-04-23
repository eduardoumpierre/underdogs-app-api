<?php

namespace App\Http\Controllers;

use App\Repositories\BadgeRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BadgeController extends Controller
{
    private $badgeRepository;

    /**
     * BadgeRepository constructor.
     * @param BadgeRepository $br
     */
    public function __construct(BadgeRepository $br)
    {
        $this->badgeRepository = $br;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->badgeRepository->findAll();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getOne(int $id): Model
    {
        return $this->badgeRepository->findOneById($id);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'experience' => 'required|numeric'
        ]);

        return response()->json($this->badgeRepository->create($request->all()), Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'experience' => 'required|numeric'
        ]);

        return response()->json($this->badgeRepository->update($request->all(), $id));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        return response()->json($this->badgeRepository->delete($id), Response::HTTP_NO_CONTENT);
    }
}
