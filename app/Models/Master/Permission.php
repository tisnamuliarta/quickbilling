<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function children(): HasMany
    {
        return $this->hasMany(Permission::class, 'parent_id');
    }
}
