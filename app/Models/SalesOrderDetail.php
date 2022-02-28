<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderDetail extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'order_detail_id';
    protected $table = 'sales_order_detail';
}
