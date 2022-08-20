<?php

namespace App\Models\Transactions;

use IFRS\Models\LineItem as LineItemModel;
use OwenIt\Auditing\Contracts\Auditable;

class LineItem extends LineItemModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;
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
        'vat_id',
        'contact_id',
        'service_date',
        'sub_total',
        'classification_id',
        'status',
        'sku',
        'base_line_id',
        'price',
        'warehouse_id',
        'tax_name',
        'classification',
        'created_by',
    ];

    protected $appends = [
        'code',
        'whs_name',
        'unit',
        'default_currency_symbol',
    ];

    protected $casts = [
        'check_payment' => 'boolean',
    ];

    public function getWhsNameAttribute(): mixed
    {
        return ($this->warehouse) ? $this->warehouse->code : null;
    }

    /**
     * @return mixed
     */
    public function getCodeAttribute(): mixed
    {
        return ($this->item) ? $this->item->code : null;
    }

    /**
     * @return mixed
     */
    public function getUnitAttribute()
    {
        return $this->sku;
    }

    /**
     * @return mixed
     */
    public function getDefaultCurrencySymbolAttribute()
    {
        return auth()->user()->entity->currency->currency_code;
    }
}
