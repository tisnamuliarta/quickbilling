<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $incrementing = false;
    public $keyType = 'string';
    protected $primaryKey = 'currency_code';
    protected $table = 'currency';
}
