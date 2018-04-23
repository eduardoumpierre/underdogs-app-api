<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ProductIngredientInterface
{
    public function findAll(): Collection;

    public function findOneById(int $id): Model;

    public function create(array $params): Model;

    public function update(array $params, int $id): Model;

    public function delete(int $id);
}