<?php

namespace App\Models\Transactions;

use Carbon\Carbon;
use IFRS\Exceptions\AdjustingReportingPeriod;
use IFRS\Exceptions\ClosedReportingPeriod;
use IFRS\Exceptions\InvalidCurrency;
use IFRS\Exceptions\InvalidTransactionDate;
use IFRS\Exceptions\InvalidTransactionType;
use IFRS\Models\Entity;
use IFRS\Models\ReportingPeriod;
use IFRS\Models\Transaction as TransactionModel;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class Transaction extends TransactionModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

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

    /**
     * Relate LineItems to Transaction.
     * @throws AdjustingReportingPeriod
     * @throws InvalidTransactionDate
     * @throws ClosedReportingPeriod
     * @throws InvalidCurrency
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

        if (!isset($this->exchange_rate_id) && !is_null($entity)) {
            $this->exchange_rate_id = $entity->default_rate->id;
        }

        if (isset($this->exchange_rate_id) && !isset($this->currency_id)) {
            $this->currency_id = $this->exchangeRate->currency_id;
        }

        $this->transaction_date = !isset($this->transaction_date) ? Carbon::now() : Carbon::parse($this->transaction_date);

        $period = ReportingPeriod::getPeriod(Carbon::parse($this->transaction_date), $entity);

        if (ReportingPeriod::periodStart($this->transaction_date, $entity)->eq(Carbon::parse($this->transaction_date))) {
            throw new InvalidTransactionDate();
        }

        if ($period->status == ReportingPeriod::CLOSED) {
            throw new ClosedReportingPeriod($period->calendar_year);
        }

        if ($period->status == ReportingPeriod::ADJUSTING && $this->transaction_type != \IFRS\Models\Transaction::JN) {
            throw new AdjustingReportingPeriod();
        }

        if (in_array($this->account->account_type, config('ifrs.single_currency')) &&
            $this->account->currency_id != $this->currency_id &&
            $this->currency_id != $entity->currency_id) {
            throw new InvalidCurrency("Transaction", $this->account);
        }

        if (!isset($this->currency_id)) {
            $this->currency_id = $this->account->currency_id;
        }
        throw new \Exception($this->transaction_type);
        if (is_null($this->transaction_no)) {
            $this->transaction_no = Transaction::transactionNo(
                $this->transaction_type,
                Carbon::parse($this->transaction_date),
                $entity
            );
        }

        if (!isset($this->exchange_rate_id)) {
            $this->exchange_rate_id = Auth::user()->entity->default_rate->id;
        }

        if ($this->isDirty('transaction_type') && $this->transaction_type != $this->getOriginal('transaction_type') && !is_null($this->id)) {
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
     * @param string $type
     * @param Carbon|null $transaction_date
     * @param Entity|null $entity
     * @return string
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
                ->where("transaction_type", $type)
                ->where("transaction_date", ">=", $periodStart)
                ->where("entity_id", '=', $entity->id)
                ->count() + 1;

        return $type . "-" . str_pad((string)$periodCount, 2, "0", STR_PAD_LEFT)
            . $month .
            str_pad((string)$nextId, 5, "0", STR_PAD_LEFT);
    }
}
