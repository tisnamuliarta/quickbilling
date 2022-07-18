<?php

namespace App\Http\Controllers\BusinessPartner;

use App\Http\Controllers\Controller;
use App\Models\Documents\Document;
use IFRS\Models\Transaction;
use Illuminate\Http\Request;

class ContactTransactionController extends Controller
{
    /**
     * get contact transactions
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     */
    public function index(Request $request, int $id)
    {
        $first = Document::where('contact_id', $id)
            ->select(
                'id',
                'contact_id',
                'transaction_no',
                'transaction_date',
                'transaction_type',
                'narration',
                'due_date',
                'main_account_amount',
                'status'
            );

        $transactions = Transaction::where('contact_id', $id)
            ->union($first)
            ->with(['account'])
            ->select(
                'id',
                'contact_id',
                'transaction_no',
                'transaction_date',
                'transaction_type',
                'narration',
                'due_date',
                'main_account_amount',
                'status'
            )->get();

        return $this->success([
            'data' => $transactions
        ]);
    }
}
