<?php

namespace App\Models\Inventory;

use IFRS\Models\Account;
use IFRS\Models\Vat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Item extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];

    /**
     * cast attribute
     *
     * @var array
     */
    protected $casts = [
        'reorder_point' => 'decimal:2',
        'onhand' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'commision_rate' => 'decimal:2',
        'minimum_stock' => 'double',
    ];

    protected $appends = [
        'whs_name',
        'available_qty',
        'item_group'
    ];

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
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class);
    }

    /**
     * @return BelongsTo
     */
    public function salesAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'revenue_account_id');
    }

    /**
     * @return BelongsTo
     */
    public function purchaseAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'expense_account_id');
    }

    /**
     * @return BelongsTo
     */
    public function inventoryAccounts(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'inventory_account');
    }

    /**
     * @return BelongsTo
     */
    public function salesTax(): BelongsTo
    {
        return $this->belongsTo(Vat::class, 'sell_tax_id');
    }

    /**
     * @return BelongsTo
     */
    public function purchaseTax(): BelongsTo
    {
        return $this->belongsTo(Vat::class, 'buy_tax_id');
    }

    /**
     * @return BelongsTo
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * @return HasMany
     */
    public function itemWarehouse(): HasMany
    {
        return $this->hasMany(ItemWarehouse::class);
    }

    public function getWhsNameAttribute()
    {
        $warehouse = Warehouse::first();

        return $warehouse->code;
    }

    /**
     * @return mixed
     */
    public function getAvailableQtyAttribute()
    {
        return floatval($this->on_hand_qty) - floatval($this->committed_qty) + floatval($this->ordered_qty);
    }

    /**
     * @return string
     */
    public function getItemGroupAttribute()
    {
        return match ($this->item_group_id) {
            1 => 'Inventory',
            2 => 'Non inventory',
            3 => 'Service',
            default => '',
        };
    }
}
