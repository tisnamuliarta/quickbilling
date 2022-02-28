<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'option_id';
    protected $table = 'options';
}
