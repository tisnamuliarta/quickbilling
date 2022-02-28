<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderStatus extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'sales_order_status';
    protected $table = 'sales_order_status_id';
}
