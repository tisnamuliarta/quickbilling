<?php

namespace App\Models\Master;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function children()
    {
        return $this->hasMany(Permission::class, 'parent_id');
    }
}
