<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderHeader extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'order_header_id';
    protected $table = 'sales_order_header';
}
