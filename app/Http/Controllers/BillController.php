<?php

namespace App\Http\Controllers;

use App\Repositories\BillProductRepository;
use App\Repositories\BillRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    private $billRepository;
    private $billProductRepository;
    private $userRepository;

    /**
     * BillController constructor.
     * @param BillRepository $br
     * @param BillProductRepository $bpr
     * @param UserRepository $ur
     */
    public function __construct(BillRepository $br, BillProductRepository $bpr, UserRepository $ur)
    {
        $this->billRepository = $br;
        $this->billProductRepository = $bpr;
        $this->userRepository = $ur;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->billRepository->findAll();
    }

    /**
     * @param int $id
     * @return Collection|Model|null|static|static[]
     */
    public function getOne(int $id)
    {
        if (Auth::user()->role == 0) {
            return $this->billRepository->findOneByUserIdWithProducts($id);
        }

        return $this->billRepository->findOneByIdWithProducts($id);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'users_id' => 'required|numeric',
            'cards_id' => 'required|numeric'
        ]);

        return response()->json($this->billRepository->create($request->all()), Response::HTTP_CREATED);
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
            'users_id' => 'required|numeric',
            'cards_id' => 'required|numeric'
        ]);

        return response()->json($this->billRepository->update($request->all(), $id));
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(int $id): JsonResponse
    {
        return response()->json($this->billRepository->delete($id), Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addProduct(Request $request)
    {
        $this->validate($request, [
            'bills_id' => 'required|numeric',
            'products' => 'required|array'
        ]);

        return response()->json($this->billProductRepository->insert($request->all()), Response::HTTP_CREATED);
    }

    /**
     * @param int $id
     * @param int $productId
     * @return JsonResponse
     */
    public function deleteProduct(int $id, int $productId)
    {
        return response()->json($this->billProductRepository->deleteOneProductById($id, $productId));
    }

    /**
     * @param Request $request
     * @return Collection|Model
     * @throws \Illuminate\Validation\ValidationException
     */
    public function checkout(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric'
        ]);

        return $this->billRepository->checkout($request->all()['id']);
    }
}
