<?php

namespace App\Models\Documents;

use App\Models\Financial\Currency;
use App\Models\Inventory\Contact;
use App\Models\Inventory\Warehouse;
use App\Models\Sales\SalesPerson;
use Carbon\Carbon;
use IFRS\Models\Entity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;

/**
 * Class Transaction
 *
 * @property Carbon $transaction_date
 * @property string $transaction_no
 * @property string $transaction_type
 * @property string $narration
 * @property float $main_account_amount
 */
class Document extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTags;
    use Searchable;
    use LogsActivity;

    protected $guarded = [];

    /**
     * Transaction Types
     *
     * @var string
     */
    const SO = 'SO';

    const SD = 'SD';

    const SQ = 'SQ';

    const SR = 'SR';

    const PO = 'PO';

    const PQ = 'PQ';

    const GR = 'GR';

    const GN = 'GN';

    const GE = 'GE';

    const GI = 'GI';

    const PE = 'PE';

    const PI = 'PI';

    const PR = 'PR';

    public function toSearchableArray()
    {
        return [
            'transaction_no' => $this->transaction_no,
            'transaction_date' => $this->transaction_date,
            'transaction_type' => $this->type,
            'contact' => $this->contact->name,
        ];
    }

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
        'main_account_amount' => 'double',
        'transaction_date' => 'datetime:Y-m-d',
        'due_date' => 'datetime:Y-m-d',
    ];

    protected $appends = [
        'type',
    ];

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

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function getIssueAtAttribute($value): string
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * Instance Type Translator.
     *
     * @return string
     */
    public function getTypeAttribute(): string
    {
        return Document::getType($this->transaction_type);
    }

    /**
     * Get Human Readable Transaction type
     *
     * @param string $type
     *
     * @return string
     */
    public static function getType($type): string
    {
        return config('ifrs')['documents'][$type];
    }

    /**
     * @return BelongsTo
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(DocumentItem::class);
    }

    /**
     * @return HasMany
     */
    public function lineItems(): HasMany
    {
        return $this->hasMany(DocumentItem::class);
    }

    /**
     * @return HasMany
     */
    public function taxDetails(): HasMany
    {
        return $this->hasMany(DocumentItemTax::class);
    }

    /**
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'currency_code');
    }

    /**
     * @return BelongsTo
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * @return HasMany
     */
    public function histories(): HasMany
    {
        return $this->hasMany(DocumentHistory::class);
    }

    /**
     * @return HasMany
     */
    public function totals(): HasMany
    {
        return $this->hasMany(DocumentTotal::class);
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function child(): HasMany
    {
        return $this->hasMany(Document::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function salesOrder(): HasMany
    {
        return $this->hasMany(Document::class, 'parent_id', 'id')
            ->where('type', '=', 'SO');
    }

    // this is a recommended way to declare event handlers

    /**
     * @return HasMany
     */
    public function salesPerson(): HasMany
    {
        return $this->hasMany(SalesPerson::class);
    }
}
