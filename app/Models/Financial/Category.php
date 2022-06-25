<?php

namespace App\Models\Financial;

use IFRS\Models\Category as IfrsCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends IfrsCategory
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'category_type',
        'descriptions',
        'entity_id',
    ];
}
