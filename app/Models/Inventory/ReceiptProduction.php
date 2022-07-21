<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptProduction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function lineItems()
    {
        return $this->hasMany(ReceiptLine::class, 'production_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
