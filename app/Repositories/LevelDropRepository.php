<?php

namespace App\Repositories;

use App\Interfaces\LevelDropInterface;
use App\LevelDrop;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class LevelDropRepository implements LevelDropInterface
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return LevelDrop::query()->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return LevelDrop::query()->findOrFail($id);
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        return LevelDrop::query()->create($params);
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function update(array $params, int $id): Model
    {
        $product = LevelDrop::query()->findOrFail($id);
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
        LevelDrop::query()->findOrFail($id)->delete();

        return null;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findOneRandomByLevelId(int $id)
    {
        return LevelDrop::query()->where('levels_id', '=', $id)->inRandomOrder()->get()->first();
    }

    /**
     * @param int $id
     * @return Collection|static[]
     */
    public function findAllByLevelId(int $id)
    {
        return LevelDrop::query()
            ->from('levels_drops AS ld')
            ->select(['d.id', 'd.description'])
            ->join('drops AS d', 'ld.drops_id', '=', 'd.id')
            ->where('ld.levels_id', '=', $id)
            ->get();
    }

    /**
     * @param array $drops
     * @param int|null $id
     * @return bool
     */
    public function insert(array $drops, int $id = null)
    {
        if ($id) {
            LevelDrop::query()->where('levels_id', '=', $id)->delete();
        }
        $data = [];

        foreach ($drops as $key => $val) {
            $data[$key] = [
                'drops_id' => $val,
                'levels_id' => $id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        return LevelDrop::query()->insert($data);
    }
}
