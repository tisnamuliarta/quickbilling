<?php

namespace App\Models\Documents;

use App\Models\Financial\Currency;
use App\Models\Inventory\Contact;
use App\Models\Sales\SalesPerson;
use Carbon\Carbon;
use IFRS\Models\Entity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Document extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use SoftDeletes;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
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
    public function lineItems()
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'currency_code');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function histories()
    {
        return $this->hasMany(DocumentHistory::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function totals()
    {
        return $this->hasMany(DocumentTotal::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function parent()
    {
        return $this->belongsTo(Document::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function child()
    {
        return $this->hasMany(Document::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function salesOrder()
    {
        return $this->hasMany(Document::class, 'parent_id', 'id')
            ->where('type', '=', 'SO');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function salesPerson()
    {
        return $this->hasMany(SalesPerson::class);
    }

    /**
     * @return mixed
     */
    public function getDefaultCurrencyCodeAttribute()
    {
        return $this->currency->code;
    }

    /**
     * @return mixed
     */
    public function getDefaultCurrencySymbolAttribute()
    {
        return $this->currency->symbol;
    }

    // this is a recommended way to declare event handlers
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($document) {
            // before delete() method call this
            $document->items()->delete();
            $document->taxDetails()->delete();
            // do the rest of the cleanup...
        });
    }
}
