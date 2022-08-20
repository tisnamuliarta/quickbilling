<?php

namespace App\Models\Financial;

use IFRS\Models\Account as IfrsAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    ];

    protected $appends = [
        'balance',
    ];

    /**
     */
    public function getBalanceAttribute()
    {
        return ($this->entity) ? $this->closingBalance()[1] : 0;
    }
}
