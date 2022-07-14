<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Documents\Document;
use IFRS\Models\Transaction;

class CopyDocumentController extends Controller
{
    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function copyDocument($id)
    {
        try {
            $documents = Document::where('id', $id)
                ->with(['lineItems', 'taxDetails', 'entity', 'salesPerson', 'contact'])
                ->first();
            if (! $documents) {
                $documents = Transaction::where('id', $id)
                    ->with(['lineItems', 'taxDetails', 'entity', 'salesPerson', 'contact'])
                    ->first();
            }

            return $this->success(['data' => $documents]);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }
}
