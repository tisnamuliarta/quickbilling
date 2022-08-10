<?php

namespace App\Traits;

use Spatie\Tags\Tag;

trait TagHelper
{
    /**
     * @param $name
     *
     * @return void
     */
    public function createTag($name)
    {
        Tag::updateOrCreate([
            'name' => $name,
            'slug' => str()->slug($name)
        ]);
    }
}
