<?php

namespace App\Http\Controllers;

use App\Repositories\ScheduleRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ScheduleController extends Controller
{
    private $scheduleRepository;

    /**
     * ScheduleRepository constructor.
     * @param ScheduleRepository $br
     */
    public function __construct(ScheduleRepository $br)
    {
        $this->scheduleRepository = $br;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->scheduleRepository->findAll();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getOne(int $id): Model
    {
        return $this->scheduleRepository->findOneById($id);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'date' => 'required|date'
        ]);

        return response()->json($this->scheduleRepository->create($request->all()), Response::HTTP_CREATED);
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
            'title' => 'required',
            'description' => 'required',
            'date' => 'required|date'
        ]);

        return response()->json($this->scheduleRepository->update($request->all(), $id));
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(int $id): JsonResponse
    {
        return response()->json($this->scheduleRepository->delete($id), Response::HTTP_NO_CONTENT);
    }
}
