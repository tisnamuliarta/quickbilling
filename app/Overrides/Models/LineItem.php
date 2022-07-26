<?php

/**
 * Eloquent IFRS Accounting
 *
 * @author    Edward Mungai
 * @copyright Edward Mungai, 2020, Germany
 * @license   MIT
 */

namespace IFRS\Models;

use App\Models\Inventory\Item;
use App\Models\Inventory\Warehouse;
use App\Models\Transactions\Classification;
use Carbon\Carbon;
use IFRS\Exceptions\MultipleVatError;
use IFRS\Exceptions\NegativeAmount;
use IFRS\Exceptions\NegativeQuantity;
use IFRS\Exceptions\PostedTransaction;
use IFRS\Interfaces\Recyclable;
use IFRS\Interfaces\Segregatable;
use IFRS\Traits\ModelTablePrefix;
use IFRS\Traits\Recycling;
use IFRS\Traits\Segregating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LineItem
 *
 *
 * @property Entity $entity
 * @property Transaction $transaction
 * @property Vat $vat
 * @property Account $account
 * @property int $quantity
 * @property float $amount
 * @property bool $vat_inclusive
 * @property Carbon $destroyed_at
 * @property Carbon $deleted_at
 */
class LineItem extends Model implements Recyclable, Segregatable
{
    use Segregating;
    use SoftDeletes;
    use Recycling;
    use ModelTablePrefix;

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
    ];

    protected $appends = [
        'code',
        'whs_name',
        'unit',
        'default_currency_symbol',
    ];

    /**
     * Line Item Vats
     *
     * @var array
     */
    private array $vats = [];

    /**
     * Check if Vat already exists.
     *
     * @param  int|null  $id
     * @return int|false
     */
    private function vatExists(int $id = null): bool|int
    {
        return collect($this->vats)->search(
            function ($vat, $key) use ($id) {
                return $vat->id == $id;
            }
        );
    }

    /**
     * Create applied vat objects for the vats being staged.
     *
     * @return void
     */
    private function applyVats(): void
    {
        $itemAmount = $this->amount * $this->quantity;

        foreach ($this->vats as $vat) {
            $tax = $this->vat_inclusive ?
                $itemAmount - ($itemAmount / (1 + ($vat->rate / 100))) : $itemAmount * $vat->rate / 100;

            AppliedVat::firstOrCreate([
                'vat_id' => $vat->id,
                'line_item_id' => $this->id,
                'amount' => $tax,
            ]);
            $itemAmount += $this->compound_vat ? $tax : 0;
        }
    }

    /**
     * Construct new LineItem
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        if (! isset($attributes['credited'])) {
            $attributes['credited'] = false;
        }
        if (! isset($attributes['quantity'])) {
            $attributes['quantity'] = 1;
        }
        parent::__construct($attributes);
    }

    /**
     * Instance Identifier.
     *
     * @param  bool  $type
     * @return string
     */
    public function toString(bool $type = false): string
    {
        $classname = explode('\\', self::class);
        $description = $this->account->toString().' for '.$this->amount * $this->quantity;

        return $type ? array_pop($classname).': '.$description : $description;
    }

    /**
     * LineItem Ledgers.
     *
     * @return HasMany
     */
    public function ledgers(): HasMany
    {
        return $this->hasMany(Ledger::class, 'line_item_id', 'id');
    }

    /**
     * LineItem Transaction.
     *
     * @return BelongsTo
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * LineItem Account.
     *
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * @return BelongsTo
     */
    public function vat(): BelongsTo
    {
        return $this->belongsTo(Vat::class);
    }

    /**
     * @return BelongsTo
     */
    public function classification(): BelongsTo
    {
        return $this->belongsTo(Classification::class);
    }

    /**
     * LineItem Applied VATs.
     *
     * @return HasMany
     */
    public function appliedVats(): HasMany
    {
        return $this->hasMany(AppliedVat::class);
    }

    /**
     * @return BelongsTo
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * @return mixed
     */
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

    /**
     * Total Vat amount of the LineItem.
     *
     * @return array
     */
    public function getVatAttribute(): array
    {
        $vats = ['total' => 0];
        foreach ($this->appliedVats as $appliedVat) {
            $vats['total'] += $appliedVat->amount;
            if (array_key_exists($appliedVat->vat->code, $vats)) {
                $vats[$appliedVat->vat->code] += $appliedVat->amount;
            } else {
                $vats[$appliedVat->vat->code] = $appliedVat->amount;
            }
        }

        return $vats;
    }

    /**
     * LineItem attributes.
     *
     * @return object
     */
    public function attributes(): object
    {
        return (object) $this->attributes;
    }

    /**
     * Add Vat to LineItem Vats.
     *
     * @param  Vat  $vat
     * @return bool
     *
     * @throws MultipleVatError
     * @throws PostedTransaction
     */
    public function addVat(Vat $vat): bool
    {
        if (count($this->ledgers) > 0) {
            throw new PostedTransaction('add Vat to');
        }

        if ($vat->rate == 0 && $this->compound_vat) {
            throw new MultipleVatError('Zero rated taxes cannot be applied to a compound vat Line Item');
        }

        if ($this->vat_inclusive && $this->compound_vat) {
            throw new MultipleVatError('Vat inclusive Line Items cannot have compound Vat');
        }

        $this->getVats();

        if (count($this->vats) > 0 && $this->vat_inclusive) {
            throw new MultipleVatError('Vat inclusive Line Items cannot have more than one Vat');
        }

        if ($this->vatExists($vat->id) === false) {
            $this->vats[] = $vat;

            return true;
        }

        return false;
    }

    /**
     * Get LineItem Vats.
     *
     * @return array
     */
    public function getVats(): array
    {
        foreach ($this->appliedVats as $appliedVat) {
            if ($this->vatExists($appliedVat->vat_id) === false) {
                $this->vats[] = $appliedVat->vat;
            }
        }

        return $this->vats;
    }

    /**
     * Remove Vat from LineItem Vats.
     *
     * @param  Vat  $vat
     *
     * @throws PostedTransaction
     */
    public function removeVat(Vat $vat): void
    {
        if (count($this->ledgers) > 0) {
            throw new PostedTransaction('remove Vat from');
        }

        if ($this->compound_vat) {
            $this->vats = [];
            AppliedVat::all()->delete();
        } else {
            $key = $this->vatExists($vat->id);
            if ($key !== false) {
                unset($this->vats[$key]);
            }

            AppliedVat::where([
                'line_item_id' => $this->id,
                'vat_id' => $vat->id,
            ])->first()->delete();
        }

        // reload applied vats to reflect changes
        $this->load('appliedVats');
    }

    /**
     * Validate LineItem.
     *
     * @param  array  $options
     * @return bool
     *
     * @throws NegativeAmount
     * @throws NegativeQuantity
     * @throws PostedTransaction
     */
    public function save(array $options = []): bool
    {
        if ($this->amount < 0) {
            throw new NegativeAmount('LineItem');
        }

        if ($this->quantity < 0) {
            throw new NegativeQuantity();
        }

        if (! is_null($this->transaction) && count($this->transaction->ledgers) > 0 && $this->isDirty()) {
            throw new PostedTransaction('change a LineItem of');
        }

        $save = parent::save();
        $this->applyVats();

        // reload vats to reflect changes
        $this->load('appliedVats');

        return $save;
    }
}
