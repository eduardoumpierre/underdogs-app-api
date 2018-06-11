<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $userRepository;

    /**
     * AuthController constructor.
     * @param UserRepository $ur
     */
    public function __construct(UserRepository $ur)
    {
        $this->userRepository = $ur;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json($this->userRepository->findOneById($request->user()->id));
    }
}
