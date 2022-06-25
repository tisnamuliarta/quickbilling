<?php

namespace App\Models\Financial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use IFRS\Models\Account as IfrsAccount;

class Account extends IfrsAccount
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'account_type',
        'account_id',
        'currency_id',
        'category_id',
        'entity_id',
        'description',
        'code',
        'parent_id',
        'opening_balance_date',
        'opening_balance_amount',
    ];
}
