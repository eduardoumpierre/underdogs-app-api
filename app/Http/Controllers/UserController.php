<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private $userRepository;

    /**
     * UserRepository constructor.
     * @param UserRepository $ur
     */
    public function __construct(UserRepository $ur)
    {
        $this->userRepository = $ur;
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function getAll(Request $request): Collection
    {
        if ($request->get('active')) {
            return $this->userRepository->findAllWithActiveStatus();
        }

        return $this->userRepository->findAll();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getOne(int $id): Model
    {
        return $this->userRepository->findOneById($id);
    }

    /**
     * @return Collection|static[]
     */
    public function getOnlineUsers()
    {
        return $this->userRepository->findAllOnline();
    }

    /**
     * @return Model|static
     */
    public function getOnlineUsersStats()
    {
        return $this->userRepository->findOnlineUsersStats();
    }

    /**
     * @return Collection|static[]
     */
    public function getRanking()
    {
        return $this->userRepository->findRanking();
    }

    /**
     * @param int $id
     * @return Collection|static[]
     */
    public function getAchievements(int $id)
    {
        return $this->userRepository->findAchievementsByUserId($id);
    }

    /**
     * @param int $id
     * @return Collection|static[]
     */
    public function getDrops(int $id)
    {
        return $this->userRepository->findDropsByUserId($id);
    }

    /**
     * @param int $id
     * @return Collection|static[]
     */
    public function getBadges(int $id)
    {
        return $this->userRepository->findBadgesByUserId($id);
    }

    /**
     * @param int $id
     * @return array
     */
    public function getProfileData(int $id)
    {
        $response = [];

        $response['achievements'] = $this->getAchievements($id);
        $response['badges'] = $this->getBadges($id);
        $response['drops'] = $this->getDrops($id);

        return $response;
    }

    /**
     * @param int $id
     * @return Collection|Model|static|static[]
     */
    public function getFacebookId(int $id)
    {
        return $this->userRepository->findOneByFacebookId($id);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createFacebookAccountFirstStep(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'nullable|unique:users',
            'facebook_id' => 'required:numeric'
        ]);

        return response()->json($this->userRepository->create($request->all(), true), Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createFacebookAccountSecondStep(Request $request, int $id): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|unique:users,email,' . $id,
            'cpf' => 'required|unique:users,cpf,' . $id,
            'birthday' => 'required'
        ]);

        return response()->json($this->userRepository->updateFacebookUser($request->all(), $id));
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
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'cpf' => 'required|unique:users',
            'password' => 'required',
            'birthday' => 'required'
        ]);

        return response()->json($this->userRepository->create($request->all()), Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createQuickUser(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'cpf' => 'required|unique:users'
        ]);

        return response()->json($this->userRepository->create($request->all(), true), Response::HTTP_CREATED);
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
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|unique:users,email,' . $id
        ]);

        return response()->json($this->userRepository->update($request->all(), $id));
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(int $id): JsonResponse
    {
        return response()->json($this->userRepository->delete($id), Response::HTTP_NO_CONTENT);
    }
}
