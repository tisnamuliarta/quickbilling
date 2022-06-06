<?php

namespace App\Models\Financial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use IFRS\Models\Currency as IfrsCurrency;

class Currency extends IfrsCurrency
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'currency_code',
        'currency_symbol',
        'entity_id',
    ];
}
