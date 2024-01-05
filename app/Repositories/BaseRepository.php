<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    abstract public function create(array $attributes);
    abstract public function update($model, array $attributes);
    abstract public function forceDelete($model);
}
