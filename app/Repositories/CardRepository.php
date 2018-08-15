<?php

namespace App\Repositories;

use App\Bill;
use App\Interfaces\CardInterface;
use App\Card;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CardRepository implements CardInterface
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Card::query()->get();
    }

    /**
     * @return Collection|\Illuminate\Support\Collection|static[]
     */
    public function findAllInactive()
    {
        $activeCardsIds = Bill::query()
            ->select('cards_id AS id')
            ->where('is_active', '=', 1)->get();

        return Card::query()->whereNotIn('id', $activeCardsIds)->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return Card::query()->findOrFail($id);
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        return Card::query()->create($params);
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function update(array $params, int $id): Model
    {
        $product = Card::query()->findOrFail($id);
        $product->update($params);

        return $product;
    }

    /**
     * @param int $id
     * @return null
     * @throws \Exception
     */
    public function delete(int $id)
    {
        Card::query()->findOrFail($id)->delete();

        return null;
    }

}
