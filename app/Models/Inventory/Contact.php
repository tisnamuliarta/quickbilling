<?php

namespace App\Models\Inventory;

use App\Models\Documents\Document;
use App\Models\Financial\Currency;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function emails()
    {
        return $this->hasMany(ContactEmail::class);
    }

    public function banks()
    {
        return $this->hasMany(ContactBank::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
