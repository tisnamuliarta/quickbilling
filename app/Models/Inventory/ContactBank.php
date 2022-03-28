<?php

namespace App\Models\Inventory;

use App\Models\Master\Bank;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactBank extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['name'];

    public function banks()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }

    public function getNameAttribute()
    {
        return $this->banks->name;
    }
}
