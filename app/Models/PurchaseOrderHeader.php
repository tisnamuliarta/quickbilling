<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderHeader extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'purchase_order_header_id';
    protected $table = 'purchase_order_header';
}
