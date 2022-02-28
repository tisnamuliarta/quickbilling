<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'purchase_order_detail';
    protected $primaryKey = 'purchase_order_detail_id';
}
