<?php

namespace App\Models\Transactions;

use IFRS\Models\LineItem as LineItemModel;

class LineItem extends LineItemModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'amount',
        'quantity',
        'narration',
        'transaction_id',
        'vat_inclusive',
        'entity_id',
        'credited',
        'compound_vat',
        'item_id',
        'tax_id',
        'contact_id',
        'service_date',
        'sub_total',
        'classification',
        'status',
    ];
}
