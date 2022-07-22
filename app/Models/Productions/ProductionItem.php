<?php

namespace App\Models\Productions;

use App\Models\Inventory\Item;
use App\Models\Inventory\Resource;
use App\Models\Inventory\Warehouse;
use IFRS\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'item_code',
        'whs_code',
        'account_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'double',
        'price' => 'double',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function item()
    {
        return $this->belongsTo(Resource::class, 'item_id');
    }

    /**
     * @return mixed
     */
    public function getWhsCodeAttribute(): mixed
    {
        return ($this->warehouse) ? $this->warehouse->code : null;
    }

    /**
     * @return mixed
     */
    public function getItemCodeAttribute(): mixed
    {
        return ($this->item) ? $this->item->code : null;
    }

    public function getAccountCodeAttribute(): mixed
    {
        return ($this->account) ? $this->account->code : null;
    }
}
