<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\Documents\Document;
use App\Models\Settings\Setting;
use App\Traits\CompanyField;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use NumberToWords\NumberToWords;

class DocumentExportController extends Controller
{
    use CompanyField;

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \NumberToWords\Exception\InvalidArgumentException
     * @throws \NumberToWords\Exception\NumberToWordsException
     */
    public function print(Request $request)
    {
        $documents = Document::find($request->id);
        $company = $this->company();
        $numberToWords = new NumberToWords();
        $currencyTransformer = $numberToWords->getNumberTransformer('en');
        $amount = Str::upper($currencyTransformer->toWords(floatval($documents->amount)));
        $type = Str::upper($this->mapping($documents->type));
        $pdf = Pdf::loadView('export.document', compact('documents', 'company', 'amount', 'type'));

//        $destination_path = public_path("files/export/");
//        if (!file_exists($destination_path)) {
//            if (!mkdir($destination_path, 0777, true) && !is_dir($destination_path)) {
//                throw new \RuntimeException(sprintf(
//                    'Directory "%s" was not created',
//                    $destination_path
//                ));
//            }
//        }

        $file_name = Str::upper($documents->document_number) . '.pdf';

//        file_put_contents($destination_path . $file_name, $pdf->output());
//        return response()->download($destination_path . $file_name);
        return $pdf->download($file_name);
    }

    /**
     * @param $type
     * @return string|void
     */
    public function mapping($type)
    {
        switch ($type) {
            case 'SQ':
                return 'Sales quotations';
            case 'SO':
                return 'sales order';
            case 'SD':
                return 'sales delivery';
            case 'SI':
                return 'A/R invoice';
            case 'SP':
                return 'incoming payment';
            case 'SR':
                return 'sales return';
            case 'PQ':
                return 'purchase quotations';
            case 'PO':
                return 'purchase order';
            case 'PR':
                return 'goods receipt';
            case 'PI':
                return 'A/P invoice';
            case 'PP':
                return 'outgoing payment';
            case 'PN':
                return 'goods return';
        }
    }

    public function email(Request $request)
    {
    }
}
