<?php

namespace App\Models\Settings;

use IFRS\Models\Entity as IfrsEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class Entity extends IfrsEntity implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
}
