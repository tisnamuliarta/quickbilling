<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'reorder_point' => 'decimal:2',
        'onhand' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'commision_rate' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(ItemCategory::class);
    }
}
