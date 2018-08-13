<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BillProductInterface
{
    public function findAll(): Collection;

    public function findOneById(int $id): Model;

    public function insert(array $params);

    public function update(array $params, int $id): Model;

    public function delete(int $id);
}
