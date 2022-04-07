<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Mail\Documents\DocumentSend;
use App\Models\Documents\Document;
use App\Traits\CompanyField;
use App\Traits\DocumentHelper;
use App\Traits\FileUpload;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use NumberToWords\NumberToWords;

class DocumentExportController extends Controller
{
    use CompanyField;
    use FileUpload;
    use DocumentHelper;

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \NumberToWords\Exception\InvalidArgumentException
     * @throws \NumberToWords\Exception\NumberToWordsException
     */
    public function print(Request $request)
    {
        $documents = Document::find($request->id);
        $pdf = $this->pdfInstance($documents);

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
     * @param $documents
     * @return \Barryvdh\DomPDF\PDF
     * @throws \NumberToWords\Exception\InvalidArgumentException
     * @throws \NumberToWords\Exception\NumberToWordsException
     */
    public function pdfInstance($documents)
    {
        $company = $this->company();
        $numberToWords = new NumberToWords();
        $currencyTransformer = $numberToWords->getNumberTransformer('en');
        $amount = Str::upper($currencyTransformer->toWords(floatval($documents->amount)));
        $type = Str::upper($this->mapping($documents->type));
        return Pdf::loadView('export.document', compact('documents', 'company', 'amount', 'type'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function email(Request $request)
    {
        $messages = [
            'form.send_to.required' => 'Receiver is required',
            'form.messages.required' => 'Message is required',
        ];

        $validator = Validator::make($request->all(), [
            'form.send_to' => 'required|array',
            'form.send_to.*' => 'required|email',
            'form.messages' => 'required'
        ], $messages);

        $form = $request->form;

        $string_data = "";
        if ($validator->fails()) {
            foreach (collect($validator->messages()) as $error) {
                foreach ($error as $items) {
                    $string_data .= $items . ", \n  ";
                }
            }
            return $this->error($string_data);
        }

        try {
            $documents = (object)$request->defaultItem;
            $documents = Document::find($documents->id);
            $pdf = $this->pdfInstance($documents);
            if (count($form['cc_email']) > 0) {
                Mail::to($form['send_to'])
                    ->cc($form['cc_email'])
                    ->send(new DocumentSend($form, $documents, $pdf->output()));
            } else {
                Mail::to($form['send_to'])
                    ->send(new DocumentSend($form, $documents, $pdf->output()));
            }

            return $this->success('Email send!');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422);
        }
    }
}
