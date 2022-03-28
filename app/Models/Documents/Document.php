<?php

namespace App\Models\Documents;

use App\Models\Financial\Currency;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Document extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'default_currency_code',
        'default_currency_symbol',
//        'attachment',
//        'amount_without_tax',
//        'discount',
//        'paid',
//        'received_at',
//        'status_label',
//        'sent_at',
//        'reconciled',
//        'contact_location'
    ];

    protected $dates = ['deleted_at', 'issued_at', 'due_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'double',
        'balance_due' => 'double',
        'currency_rate' => 'double',
        'discount_amount' => 'double',
        'discount_per_line' => 'double',
        'discount_rate' => 'double',
        'sub_total' => 'double',
    ];

    /**
     * @param $value
     * @return string
     */
    public function getIssueAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(DocumentItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taxDetails()
    {
        return $this->hasMany(DocumentItemTax::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function getDefaultCurrencyCodeAttribute()
    {
        return $this->currency->code;
    }

    public function getDefaultCurrencySymbolAttribute()
    {
        return $this->currency->symbol;
    }
}
