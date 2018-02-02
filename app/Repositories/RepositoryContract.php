<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface RepositoryContract
{
    public function get(int $id, array $parameters = array());
    public function find(array $parameters = array());
    public function index(array $parameters = array());
    public function create(array $attributes);
    public function delete(Model $model);
    public function update(Model $model, array $attributes);
}