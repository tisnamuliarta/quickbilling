<?php

namespace App\Models\Inventory;

use IFRS\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemWarehouse extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'whs_name',
        'available_qty',
    ];

    /**
     * @return BelongsTo
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
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
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return mixed
     */
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
}
