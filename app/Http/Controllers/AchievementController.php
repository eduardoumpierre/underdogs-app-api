<?php

namespace App\Http\Controllers;

use App\Repositories\AchievementRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AchievementController extends Controller
{
    private $achievementRepository;

    /**
     * AchievementRepository constructor.
     * @param AchievementRepository $br
     */
    public function __construct(AchievementRepository $br)
    {
        $this->achievementRepository = $br;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->achievementRepository->findAll();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getOne(int $id): Model
    {
        return $this->achievementRepository->findOneById($id);
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
            'experience' => 'required|numeric',
            'category' => 'required|numeric',
            'entity' => 'required|numeric',
            'value' => 'required|numeric'
        ]);

        return response()->json($this->achievementRepository->create($request->all()), Response::HTTP_CREATED);
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
            'experience' => 'required|numeric',
            'category' => 'required|numeric',
            'entity' => 'required|numeric',
            'value' => 'required|numeric'
        ]);

        return response()->json($this->achievementRepository->update($request->all(), $id));
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(int $id): JsonResponse
    {
        return response()->json($this->achievementRepository->delete($id), Response::HTTP_NO_CONTENT);
    }

    public function updateAchievements(int $userId)
    {
        $this->achievementRepository->updateAchievementsByUserId($userId);
    }
}
