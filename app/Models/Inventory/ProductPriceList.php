<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPriceList extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'product_price_list_id';
    protected $table = 'product_price_list';
}
