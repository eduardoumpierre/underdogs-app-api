<?php

namespace App\Http\Controllers;

use App\Repositories\BillProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    private $userRepository;
    private $billProductRepository;

    /**
     * DashboardController constructor.
     * @param UserRepository $ur
     * @param BillProductRepository $bpr
     */
    public function __construct(UserRepository $ur, BillProductRepository $bpr)
    {
        $this->userRepository = $ur;
        $this->billProductRepository = $bpr;
    }

    /**
     * @return JsonResponse
     */
    public function getReports(): JsonResponse
    {
        $response = [];

        $response['daily'] = $this->userRepository->findDailyUsersStats();
        $response['online'] = $this->userRepository->findOnlineUsersStats();
        $response['products'] = $this->billProductRepository->findMostOrderedProducts();

        return response()->json($response);
    }
}
