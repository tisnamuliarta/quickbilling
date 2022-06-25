<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $primaryKey = 'currency_rate_id';

    protected $table = 'currency_rate';
}
