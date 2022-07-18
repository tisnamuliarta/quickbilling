<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function basePrice()
    {
        return $this->belongsTo(PriceList::class, 'base_id');
    }
}
