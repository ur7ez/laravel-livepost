<?php

namespace Database\Factories\Helpers;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class FactoryHelper
{
    /**
     * Get a random model ID from Database
     * @param string | HasFactory $model
     * @return int
     * @throws \Exception
     */
    public static function getRandomModelId(string $model): int
    {
        // get model count
        $count = $model::query()->count();
        if ($count === 0) {
            return $model::factory()->create()->id;
        }
        // generate random number between 1 and model count
        return random_int(1, $count);
    }
}
