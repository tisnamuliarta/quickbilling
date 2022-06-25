<?php

namespace App\Models\Financial;

use IFRS\Models\Currency as IfrsCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
