<?php

/**
 * Eloquent IFRS Accounting
 *
 * @author    Edward Mungai
 * @copyright Edward Mungai, 2020, Germany
 * @license   MIT
 */

namespace IFRS\Models;

use App\Models\Inventory\Contact;
use App\Models\Sales\SalesPerson;
use App\Overrides\Models\Account;
use Carbon\Carbon;
use IFRS\Exceptions\AdjustingReportingPeriod;
use IFRS\Exceptions\ClosedReportingPeriod;
use IFRS\Exceptions\HangingClearances;
use IFRS\Exceptions\InvalidCurrency;
use IFRS\Exceptions\InvalidTransactionDate;
use IFRS\Exceptions\InvalidTransactionType;
use IFRS\Exceptions\MissingLineItem;
use IFRS\Exceptions\PostedTransaction;
use IFRS\Exceptions\RedundantTransaction;
use IFRS\Exceptions\UnbalancedTransaction;
use IFRS\Exceptions\UnpostedAssignment;
use IFRS\Interfaces\Assignable;
use IFRS\Interfaces\Clearable;
use IFRS\Interfaces\Recyclable;
use IFRS\Interfaces\Segregatable;
use IFRS\Traits\Assigning;
use IFRS\Traits\Clearing;
use IFRS\Traits\ModelTablePrefix;
use IFRS\Traits\Recycling;
use IFRS\Traits\Segregating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class Transaction
 *
 *
 * @property Entity $entity
 * @property ExchangeRate $exchangeRate
 * @property Account $account
 * @property Currency $currency
 * @property Carbon $transaction_date
 * @property string $reference
 * @property string $transaction_no
 * @property string $transaction_type
 * @property string $narration
 * @property bool $credited
 * @property bool $compound
 * @property float $amount
 * @property float $main_account_amount
 * @property Carbon $destroyed_at
 * @property Carbon $deleted_at
 */
class Transaction extends Model implements Segregatable, Recyclable, Clearable, Assignable, Auditable
{
    use Segregating;
    use SoftDeletes;
    use Recycling;
    use Clearing;
    use Assigning;
    use ModelTablePrefix;
    use \OwenIt\Auditing\Auditable;

    /**
     * Transaction Model Name
     *
     * @var string
     */
    const MODELNAME = self::class;

    /**
     * Transaction Types
     *
     * @var string
     */
    const CS = 'CS';

    const IN = 'IN';

    const CN = 'CN';

    const RC = 'RC';

    const CP = 'CP';

    const BL = 'BL';

    const DN = 'DN';

    const PY = 'PY';

    const CE = 'CE';

    const JN = 'JN';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_id',
        'exchange_rate_id',
        'account_id',
        'transaction_date',
        'narration',
        'reference',
        'credited',
        'main_account_amount',
        'compound',
        'transaction_type',
        'transaction_no',
        'entity_id',
        'contact_id',
        'payment_term_id',
        'deposit_account_id',
        'due_date',
        'shipping_date',
        'receipt_date',
        'tracking_no',
        'notes',
        'shipping_address',
        'billing_address',
        'ship_via',
        'discount_type',
        'discount_rate',
        'discount_amount',
        'shipping_fee',
        'deposit',
        'balance_due',
        'status',
        'classification_id',
    ];

    protected $dates = [
        'transaction_date',
    ];

    protected $appends = [
        'amount',
        'date',
        'type',
        // 'assignments',
        // 'clearances',
        'isPosted',
        'isCredited',
        // 'clearedType',
        'vat',
        'assignable',
        'clearable',
        'hasIntegrity',
    ];

    protected $casts = [
        'balance_due' => 'double',
        'currency_rate' => 'double',
        'discount_amount' => 'double',
        'discount_per_line' => 'double',
        'discount_rate' => 'double',
        'deposit' => 'double',
        'main_account_amount' => 'double',
        'transaction_date' => 'datetime:Y-m-d',
    ];

    /**
     * Transaction LineItems
     *
     * @var array
     */
    private $items = [];

    /**
     * Transactions to be cleared and their clearance amounts
     *
     * @var array
     */
    private $assigned = [];

    /**
     * Compound Ledger entries for the transaction
     *
     * @var array
     */
    protected $compoundEntries = [
        Balance::CREDIT => [],
        Balance::DEBIT => [],
    ];

    /**
     * Check if LineItem already exists.
     *
     * @param  int  $id
     * @return int|false
     */
    private function lineItemExists(int $id = null)
    {
        return collect($this->items)->search(
            function ($item, $key) use ($id) {
                return $item->id == $id;
            }
        );
    }

    /**
     * Check if Assigned Transaction already exists.
     *
     * @param  int  $id
     * @return int|false
     */
    private function assignedTransactionExists(int $id = null)
    {
        return collect($this->assigned)->search(
            function ($transaction, $key) use ($id) {
                return $transaction['id'] == $id;
            }
        );
    }

    /**
     * Get the entry type for the Compound Entry.
     *
     * @param  bool  $credited
     * @return string
     */
    private static function getCompoundEntrytype(bool $credited): string
    {
        return $credited ? Balance::CREDIT : Balance::DEBIT;
    }

    /**
     * Add Compound Entry to Transaction CompoundEntries.
     *
     * @param  array  $compoundEntry
     * @param  bool  $credited
     */
    protected function addCompoundEntry(array $compoundEntry, bool $credited): void
    {
        $this->compoundEntries[
        Transaction::getCompoundEntrytype($credited)][$compoundEntry['id']
        ] = $compoundEntry['amount'];
    }

    /**
     * Construct new Transaction.
     */
    public function __construct($attributes = [])
    {
        $this->table = config('ifrs.table_prefix').'transactions';
        $attributes['transaction_date'] = ! isset($attributes['transaction_date'])
            ? Carbon::now() : Carbon::parse($attributes['transaction_date']);

        parent::__construct($attributes);
    }

    /**
     * Get Transaction Class
     *
     * @param  string  $type
     * @return string
     */
    public static function getClass($type): string
    {
        return [
            'CS' => 'CashSale',
            'IN' => 'ClientInvoice',
            'CN' => 'CreditNote',
            'RC' => 'ClientReceipt',
            'CP' => 'CashPurchase',
            'BL' => 'SupplierBill',
            'DN' => 'DebitNote',
            'PY' => 'SupplierPayment',
            'CE' => 'ContraEntry',
            'JN' => 'JournalEntry',
        ][$type];
    }

    /**
     * Get Human Readable Transaction types
     *
     * @param  array  $types
     * @return array
     */
    public static function getTypes($types): array
    {
        $typeNames = [];

        foreach ($types as $type) {
            $typeNames[] = Transaction::getType($type);
        }

        return $typeNames;
    }

    /**
     * Get Human Readable Transaction type
     *
     * @param  string  $type
     * @return string
     */
    public static function getType($type): string
    {
        return config('ifrs')['transactions'][$type];
    }

    /**
     * Instance Identifier.
     *
     * @return string
     */
    public function toString($type = false)
    {
        $amount = ' for '.number_format($this->amount, 2);

        return $type ? $this->type.': '.$this->transaction_no.$amount : $this->transaction_no.$amount;
    }

    /**
     * Transaction Ledgers.
     *
     * @return HasMany
     */
    public function ledgers(): HasMany
    {
        return $this->hasMany(Ledger::class, 'transaction_id', 'id');
    }

    /**
     * Transaction Currency.
     *
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Transaction Account.
     *
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Transaction Exchange Rate.
     *
     * @return BelongsTo
     */
    public function exchangeRate(): BelongsTo
    {
        return $this->belongsTo(ExchangeRate::class);
    }

    /**
     * Transaction Assignments.
     *
     * @return HasMany
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'transaction_id', 'id');
    }

    /**
     * Transaction Saved Line Items.
     *
     * @return HasMany
     */
    public function lineItems(): HasMany
    {
        return $this->hasMany(LineItem::class, 'transaction_id', 'id');
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
    public function salesPerson(): HasMany
    {
        return $this->hasMany(SalesPerson::class, 'document_id');
    }

    /**
     * Instance Type Translator.
     *
     * @return string
     */
    public function getTypeAttribute(): string
    {
        return Transaction::getType($this->transaction_type);
    }

    /**
     * is_posted analog for Assignment model.
     */
    public function getIsPostedAttribute(): bool
    {
        return count($this->ledgers) > 0;
    }

    /**
     * is_credited analog for Assignment model.
     *
     * @return bool
     */
    public function getIsCreditedAttribute(): bool
    {
        return boolval($this->credited);
    }

    /**
     * cleared_type analog for Assignment model.
     *
     * @return string
     */
    public function getClearedTypeAttribute(): string
    {
        return Transaction::MODELNAME;
    }

    /**
     * amount analog for Assignment model.
     *
     * @return float
     */
    public function getAmountAttribute(): float
    {
        $ledger = new Ledger();
        $amount = 0;

        if ($this->is_posted) {
            $query = $ledger->newQuery()
                ->selectRaw('SUM(amount/rate) as amount')
                ->where([
                    'transaction_id' => $this->id,
                    'entry_type' => Transaction::getCompoundEntrytype($this->credited),
                    'currency_id' => $this->currency_id,
                ]);

            if (! $this->compound) {
                $query->where('post_account', $this->account_id);
            }

            $amount = $query->get()[0]->amount;
        } else {
            foreach ($this->getLineItems() as $lineItem) {
                if ($lineItem->credited != $this->credited) {
                    $amount += $lineItem->amount * $lineItem->quantity;
                    if (! $lineItem->vat_inclusive) {
                        $amount += $lineItem->vat['total'];
                    }
                }
            }
        }

        return $amount;
    }

    /**
     * Get Transaction CompoundEntries.
     *
     * @return array
     */
    public function getCompoundEntries(): array
    {
        if ($this->compound) {
            $this->compoundEntries[
            Transaction::getCompoundEntrytype($this->credited)
            ][$this->account_id] = floatval($this->main_account_amount);

            foreach ($this->lineItems as $lineItem) {
                $this->compoundEntries[
                Transaction::getCompoundEntrytype($lineItem->credited)
                ][$lineItem->account_id] = $lineItem->amount * $lineItem->quantity;
            }
        }

        return $this->compoundEntries;
    }

    /**
     * Get Transaction LineItems.
     *
     * @return array
     */
    public function getLineItems(): array
    {
        foreach ($this->lineItems as $lineItem) {
            if ($this->lineItemExists($lineItem->id) === false) {
                $this->items[] = $lineItem;
            }
        }

        return $this->items;
    }

    /**
     * Total Vat amount of the transaction.
     *
     * @return array
     */
    public function getVatAttribute(): array
    {
        $vats = ['total' => 0];
        foreach ($this->getLineItems() as $lineItem) {
            foreach ($lineItem->vat as $type => $amount) {
                if (array_key_exists($type, $vats)) {
                    $vats[$type] += $amount;
                } else {
                    $vats[$type] = $amount;
                }
            }
        }

        return $vats;
    }

    /**
     * Transaction is assignable predicate.
     *
     * @return bool
     */
    public function getAssignableAttribute(): bool
    {
        return count($this->clearances) == 0 && in_array($this->transaction_type, Assignment::ASSIGNABLES);
    }

    /**
     * Transaction is clearable predicate.
     *
     * @return bool
     */
    public function getClearableAttribute(): bool
    {
        return count($this->assignments) == 0 && in_array($this->transaction_type, Assignment::CLEARABLES);
    }

    /**
     * Transaction date.
     *
     * @return string
     */
    public function getDateAttribute(): string
    {
        return Carbon::parse($this->transaction_date)->toFormattedDateString();
    }

    /**
     * Transaction attributes.
     *
     * @return object
     */
    public function attributes(): object
    {
        return (object) $this->attributes;
    }

    /**
     * Add LineItem to Transaction LineItems.
     *
     * @param  LineItem  $lineItem
     * @return bool
     *
     * @throws InvalidCurrency
     * @throws PostedTransaction
     * @throws RedundantTransaction
     */
    public function addLineItem(LineItem $lineItem): bool
    {
        if (in_array(
            $lineItem->account->account_type,
            config('ifrs.single_currency')
        ) && $lineItem->account->currency_id != $this->currency_id) {
            throw new InvalidCurrency('Transaction', $lineItem->account);
        }

        if (count($this->ledgers) > 0) {
            throw new PostedTransaction('add LineItem to');
        }

        if ($lineItem->account_id == $this->account_id) {
            throw new RedundantTransaction();
        }

        if (! $this->compound) {
            $lineItem->credited = ! $this->credited;
        }

        $this->getLineItems();

        if ($this->lineItemExists($lineItem->id) === false) {
            $this->items[] = $lineItem;

            return true;
        }

        return false;
    }

    /**
     * Remove LineItem from Transaction LineItems.
     *
     * @param  LineItem  $lineItem
     *
     * @throws PostedTransaction
     * @throws \IFRS\Exceptions\NegativeAmount
     * @throws \IFRS\Exceptions\NegativeQuantity
     */
    public function removeLineItem(LineItem $lineItem): void
    {
        if (count($lineItem->ledgers) > 0) {
            throw new PostedTransaction('remove LineItems from');
        }

        $key = $this->lineItemExists($lineItem->id);

        if ($key !== false) {
            unset($this->items[$key]);
        }

        if ($this->compound) {
            $entryType = Transaction::getCompoundEntrytype($lineItem->credited);
            if (array_key_exists($lineItem->account_id, $this->compoundEntries[$entryType])) {
                unset($this->compoundEntries[$entryType][$lineItem->account_id]);
            }
        }

        $lineItem->transaction()->dissociate();
        $lineItem->save();

        // reload items to reflect changes
        $this->load('lineItems');
    }

    /**
     * Get this Transaction's assigned Transactions.
     *
     * @return array
     */
    public function getAssigned(): array
    {
        return $this->assigned;
    }

    /**
     * Add Transaction to this Transaction's assigned Transactions.
     *
     * @param  array  $toBeAssigned
     *
     * @throws UnpostedAssignment
     */
    public function addAssigned(array $toBeAssigned): void
    {
        if (! Transaction::find($toBeAssigned['id'])->is_posted) {
            throw new UnpostedAssignment();
        }

        $existing = $this->assignments->where('cleared_id', $toBeAssigned['id'])->first();
        $existing?->delete();

        if ($this->assignedTransactionExists($toBeAssigned['id']) === false && $toBeAssigned['amount'] > 0) {
            if ($this->assignedAmountBalance() > $toBeAssigned['amount']) {
                $this->assigned[] = $toBeAssigned;
            } elseif ($this->assignedAmountBalance() > 0) {
                $this->assigned[] = [
                    'id' => $toBeAssigned['id'],
                    'amount' => $this->assignedAmountBalance(),
                ];
            }
        }
    }

    /**
     * Check the balance remaining after clearing the currently Assigned Transactions.
     *
     * @return float
     */
    private function assignedAmountBalance(): float
    {
        $balance = $this->balance;
        foreach ($this->assigned as $assignedSoFar) {
            $balance -= $assignedSoFar['amount'];
        }

        return $balance;
    }

    /**
     * Create assignments for the assigned transactions being staged.
     *
     * @param  int|null  $forexAccountId
     * @return void
     */
    public function processAssigned(int $forexAccountId = null): void
    {
        foreach ($this->assigned as $outstanding) {
            $cleared = Transaction::find($outstanding['id']);

            Assignment::create([
                'assignment_date' => Carbon::now(),
                'transaction_id' => $this->id,
                'forex_account_id' => $forexAccountId,
                'cleared_id' => $cleared->id,
                'cleared_type' => $cleared->cleared_type,
                'amount' => $outstanding['amount'],
            ]);
        }
    }

    /**
     * Post Transaction to the Ledger.
     *
     * @throws MissingLineItem
     * @throws UnbalancedTransaction
     */
    public function post(): void
    {
        if (empty($this->getLineItems())) {
            throw new MissingLineItem();
        }

        $this->save();

        extract($this->getCompoundEntries());
        if ($this->compound && array_sum($C) != array_sum($D)) {
            throw new UnbalancedTransaction();
        }

        Ledger::post($this);
    }

    /**
     * Relate LineItems to Transaction.
     *
     * @param  array  $options
     * @return bool
     *
     * @throws AdjustingReportingPeriod
     * @throws ClosedReportingPeriod
     * @throws InvalidCurrency
     * @throws InvalidTransactionDate
     * @throws InvalidTransactionType
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    public function save(array $options = []): bool
    {
        if (is_null($this->entity_id)) {
            $entity = Auth::user()->entity;
        } else {
            $entity = $this->entity;
        }

        if (! isset($this->exchange_rate_id) && ! is_null($entity)) {
            $this->exchange_rate_id = $entity->default_rate->id;
        }

        if (isset($this->exchange_rate_id) && ! isset($this->currency_id)) {
            $this->currency_id = $this->exchangeRate->currency_id;
        }

        $this->transaction_date = ! isset($this->transaction_date)
            ? Carbon::now() : Carbon::parse($this->transaction_date);

        $period = ReportingPeriod::getPeriod(Carbon::parse($this->transaction_date), $entity);

        if (ReportingPeriod::periodStart(
            $this->transaction_date,
            $entity
        )->eq(Carbon::parse($this->transaction_date))) {
            throw new InvalidTransactionDate();
        }

        if ($period->status == ReportingPeriod::CLOSED) {
            throw new ClosedReportingPeriod($period->calendar_year);
        }

        if ($period->status == ReportingPeriod::ADJUSTING && $this->transaction_type != Transaction::JN) {
            throw new AdjustingReportingPeriod();
        }

        if (in_array($this->account->account_type, config('ifrs.single_currency')) &&
            $this->account->currency_id != $this->currency_id &&
            $this->currency_id != $entity->currency_id) {
            throw new InvalidCurrency('Transaction', $this->account);
        }

        if (! isset($this->currency_id)) {
            $this->currency_id = $this->account->currency_id;
        }
        if (is_null($this->transaction_no)) {
            $this->transaction_no = Transaction::transactionNo(
                $this->transaction_type,
                Carbon::parse($this->transaction_date),
                $entity
            );
        }

        if (! isset($this->exchange_rate_id)) {
            $this->exchange_rate_id = Auth::user()->entity->default_rate->id;
        }

        if ($this->isDirty('transaction_type')
            && $this->transaction_type != $this->getOriginal('transaction_type') && ! is_null($this->id)) {
            throw new InvalidTransactionType();
        }

        $save = parent::save();
        $this->saveLineItems();

        // reload items to reflect changes
        $this->load('lineItems');

        return $save;
    }

    /**
     * The next Transaction number for the transaction type and transaction_date.
     *
     * @param  string  $type
     * @param  Carbon|null  $transaction_date
     * @param  Entity|null  $entity
     * @return string
     *
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    public static function transactionNo(string $type, Carbon $transaction_date = null, Entity $entity = null): string
    {
        if (is_null($entity)) {
            $entity = Auth::user()->entity;
        }

        $month = Carbon::now()->format('m');

        $periodCount = ReportingPeriod::getPeriod($transaction_date, $entity)->period_count;
        $periodStart = ReportingPeriod::periodStart($transaction_date, $entity);

        $nextId = \IFRS\Models\Transaction::withTrashed()
                ->where('transaction_type', $type)
                ->where('transaction_date', '>=', $periodStart)
                ->where('entity_id', '=', $entity->id)
                ->count() + 1;

        return $type.'-'.str_pad((string) $periodCount, 2, '0', STR_PAD_LEFT)
            .$month.
            str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Save LineItems.
     */
    private function saveLineItems(): void
    {
        if (count($this->items)) {
            $lineItem = array_pop($this->items);
            $this->lineItems()->save($lineItem);

            $this->saveLineItems();
        }
    }

    /**
     * Check Transaction Relationships.
     *
     * @return bool
     *
     * @throws HangingClearances
     * @throws PostedTransaction
     */
    public function delete(): bool
    {
        // No hanging assignments
        if (count($this->assignments) > 0) {
            throw new HangingClearances();
        }

        // No deleting posted transactions
        if (count($this->ledgers) > 0) {
            throw new PostedTransaction('delete');
        }

        // Remove clearance records
        $this->clearances->map(
            function ($clearance, $key) {
                $clearance->delete();

                return $clearance;
            }
        );

        return parent::delete();
    }

    /**
     * Check Transaction Integrity.
     */
    public function getHasIntegrityAttribute(): bool
    {
        // verify transaction ledger hashes
        return $this->ledgers->every(
            function ($ledger, $key) {
                return hash(config('ifrs')['hashing_algorithm'], $ledger->hashed()) == $ledger->hash;
            }
        );
    }
}
