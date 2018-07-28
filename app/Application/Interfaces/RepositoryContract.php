<?php

namespace Favon\Application\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface RepositoryContract
{
    public function get(int $id, array $parameters = []);

    public function find(array $parameters = []);

    public function index(array $parameters = []);

    public function create(array $attributes);

    public function delete(Model $model);

    public function update(Model $model, array $attributes);
}
