<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Documents\Document;
use App\Services\Documents\DocumentService;
use App\Services\Transactions\TransactionService;
use Carbon\Carbon;
use IFRS\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CopyDocumentController extends Controller
{
    public TransactionService $transactionService;
    public DocumentService $documentService;

    public function __construct(TransactionService $transactionService, DocumentService $documentService)
    {
        $this->documentService = $documentService;
        $this->transactionService = $transactionService;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function copyDocument(Request $request): JsonResponse
    {
        try {
            $id = $request->copyFrom;
            $to_type = $request->type;
            $documents = Document::where('id', $id)
                ->with(['lineItems', 'taxDetails', 'entity', 'salesPerson', 'contact'])
                ->first();
            if (!$documents) {
                $documents = Transaction::where('id', $id)
                    ->with(['lineItems', 'taxDetails', 'entity', 'salesPerson', 'contact'])
                    ->first();

                $documents->narration =
                    config('ifrs')['transactions'][$to_type] . ' ' .
                    $this->transactionService->generateDocNum(Carbon::now(), $to_type);
                $documents->transaction_no = $this->transactionService->generateDocNum(Carbon::now(), $to_type);
                $documents->transaction_type = $to_type;


                if (Str::contains($to_type, ['CP', 'RC', 'CN', 'BL', 'CP'])) {
                    $documents->credited = true;
                } else {
                    $documents->credited = false;
                }
            } else {
                $documents->narration =
                    config('ifrs')['documents'][$to_type] . ' ' .
                    $this->documentService->generateDocNum(Carbon::now(), $to_type);
                $documents->transaction_no = $this->documentService->generateDocNum(Carbon::now(), $to_type);
                $documents->transaction_type = $to_type;
            }

            return $this->success([
                'form' => $documents,
                'count' => 0
            ]);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422, [
                'trace' => $exception->getTrace()
            ]);
        }
    }
}
