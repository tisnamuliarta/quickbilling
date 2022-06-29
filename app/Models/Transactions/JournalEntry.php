<?php

namespace App\Models\Transactions;

use IFRS\Transactions\JournalEntry as JournalEntryModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JournalEntry extends JournalEntryModel
{
    use HasFactory;
}
