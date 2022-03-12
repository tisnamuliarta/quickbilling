<?php

namespace App\Traits;

use App\Models\Master\Category;

trait Categories
{
    /**
     * @param $name
     * @return int
     */
    public function categoryIdByName($name): int
    {
        $data = Category::where('name', $name)->first();
        if ($data) {
            return $data->id;
        }
        return 0;
    }
}
