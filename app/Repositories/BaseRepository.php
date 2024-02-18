<?php
/**
 * Created by PhpStorm.
 * User: Denis Kostaev
 * Date: 17/02/2024
 * Time: 11:36
 */

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model::all();
    }

    public function find($id)
    {
        return $this->model::findOrFail($id);
    }

}
