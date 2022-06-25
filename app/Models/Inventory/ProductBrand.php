<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $primaryKey = 'product_brand_id';

    protected $table = 'product_brands';
}
