<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPerson extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'sales_person_id';
    protected $table = 'sales_persons';
}
