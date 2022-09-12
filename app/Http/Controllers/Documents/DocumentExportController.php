<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Mail\Documents\DocumentSend;
use App\Models\Documents\Document;
use App\Services\Reports\ExportExcelService;
use App\Services\Reports\ReportService;
use App\Traits\CompanyField;
use App\Traits\DocumentHelper;
use App\Traits\FileUpload;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use IFRS\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
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
     *
     * @return Response
     *
     * @throws \NumberToWords\Exception\InvalidArgumentException
     * @throws \NumberToWords\Exception\NumberToWordsException
     */
    public function print(Request $request): Response
    {
        $transaction_type = $request->type;
        $id = $request->id;
        $documents = Document::where('id', $id)
            ->with(['lineItems'])
            ->where('transaction_type', $transaction_type)
            ->first();
        if (!$documents) {
            $documents = Transaction::where('id', $id)
                ->with(['lineItems'])
                ->where('transaction_type', $transaction_type)
                ->first();
        }

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

        $file_name = Str::upper($documents->transaction_no) . '.pdf';

//        file_put_contents($destination_path . $file_name, $pdf->output());
//        return response()->download($destination_path . $file_name);
        return $pdf->stream($file_name);
    }

    /**
     * @param $documents
     *
     * @return \Barryvdh\DomPDF\PDF
     *
     * @throws \NumberToWords\Exception\InvalidArgumentException
     * @throws \NumberToWords\Exception\NumberToWordsException
     */
    public function pdfInstance($documents): \Barryvdh\DomPDF\PDF
    {
        $company = $this->company();
        $numberToWords = new NumberToWords();
        $currencyTransformer = $numberToWords->getNumberTransformer((auth()->user()->locale));
        $amount = Str::upper($currencyTransformer->toWords(floatval($documents->main_account_amount)));
        $type = Str::upper($this->mapping($documents->transaction_type));

        App::setLocale(auth()->user()->locale);
        Carbon::setLocale(auth()->user()->locale);
        $document_date = Carbon::parse($documents->transaction_date)->isoFormat('D MMMM Y');

        return Pdf::loadView('export.document', compact(
            'documents',
            'company',
            'amount',
            'type',
            'document_date'
        ));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function email(Request $request): \Illuminate\Http\JsonResponse
    {
        $messages = [
            'form.send_to.required' => 'Receiver is required',
            'form.messages.required' => 'Message is required',
        ];

        $validator = Validator::make($request->all(), [
            'form.send_to' => 'required|array',
            'form.send_to.*' => 'required|email',
            'form.messages' => 'required',
        ], $messages);

        $form = $request->form;

        $string_data = '';
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

    /**
     * @param Request $request
     *
     * @return Response
     * @throws \IFRS\Exceptions\InvalidAccountType
     * @throws \IFRS\Exceptions\MissingAccount
     */
    public function reportPdf(Request $request): Response
    {
        App::setLocale(auth()->user()->locale);
        Carbon::setLocale(auth()->user()->locale);

        $first_date = date('Y-m-') . '01';
        $end_day = date('Y-m-t');
        $start_date = (isset($request->start_date)) ? $request->start_date : $first_date;
        $end_date = (isset($request->end_date)) ? $request->end_date : $end_day;
        $report_type = strtoupper($request->reportType);
        $account_id = $request->account_id;
        $entity = auth()->user()->entity;

        $reportService = new ReportService();
        $service = new ExportExcelService($reportService);

        $report = $service->formData($report_type, $account_id, $start_date, $end_date, $entity);

        $pdf = Pdf::loadView('export.report_pdf', [
            'report' => $report,
            'report_type' => __($report_type)
        ]);

        $file_name = Str::upper($report_type) . '.pdf';

        return $pdf->stream($file_name);
    }

    /**
     * @throws \IFRS\Exceptions\MissingAccount
     * @throws \IFRS\Exceptions\InvalidAccountType
     */
    public function reportExcel(Request $request): Response
    {
        App::setLocale(auth()->user()->locale);
        Carbon::setLocale(auth()->user()->locale);

        $first_date = date('Y-m-') . '01';
        $end_day = date('Y-m-t');
        $start_date = (isset($request->start_date)) ? $request->start_date : $first_date;
        $end_date = (isset($request->end_date)) ? $request->end_date : $end_day;
        $report_type = strtoupper($request->reportType);
        $account_id = $request->account_id;
        $entity = auth()->user()->entity;

        $reportService = new ReportService();
        $service = new ExportExcelService($reportService);

        $report = $service->formData($report_type, $account_id, $start_date, $end_date, $entity);

        App::setLocale(auth()->user()->locale);
        Carbon::setLocale(auth()->user()->locale);

        $pdf = Pdf::loadView('export.document', compact('report'));

        $file_name = Str::upper($report_type) . '.pdf';

        return $pdf->stream($file_name);
    }
}
