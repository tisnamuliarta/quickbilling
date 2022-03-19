<?php

namespace App\Services\Documents;

use App\Models\Documents\Document;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentService
{
    use ApiResponse;

    /**
     * @param $request
     * @return array
     */
    public function index($request): array
    {
        $type = (isset($request->type)) ? $request->type : 'NO';
        $options = $request->options;
        $pages = isset($options->page) ? (int)$options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int)$options->itemsPerPage : 10;
        $sorts = isset($options->sortBy[0]) ? (string)$options->sortBy[0] : "document_number";
        $order = isset($options->sortDesc[0]) ? (string)$options->sortDesc[0] : "asc";
        $offset = ($pages - 1) * $row_data;

        $result = array();
        $query = Document::selectRaw(
            " documents.*,
             'actions' as ACTIONS "
        );

        $result["total"] = $query->count();

        $all_data = $query->orderBy($sorts, $order)
            ->offset($offset)
            ->limit($row_data)
            ->get();

        $result['form'] = $this->getForm($type);
        return array_merge($result, [
            "rows" => $all_data,
        ]);
    }

    /**
     * @param $type
     * @return array
     */
    public function getForm($type): array
    {
        $form = $this->form('documents');
        $form['deposit_info'] = false;
        $form['shipping_info'] = false;
        $form['withholding_info'] = false;
        $form['type'] = $type;
        $form['issued_at'] = Carbon::now()->format('Y-m-d');
        $form['due_at'] = Carbon::now()->addDay(30)->format('Y-m-d');
        $form['payment_term_id'] = 1;
        $form['discount_type'] = 'Percent';
        $form['withholding_type'] = 'Percent';
        $form['tax_details'] = [];
        $form['document_number'] = $this->generateDocNum(date('Y-m-d H:i:s'), $type);
        $form['temp_id'] = mt_rand(100000, 999999999999);

        return $form;
    }

    /**
     * @param $request
     * @param $type
     * @param null $id
     * @return array
     */
    public function formData($request, $type, $id = null): array
    {
        $request->mergeIfMissing([
            'company_id' => session('company_id'),
        ]);

        $request->request->remove('tags');
        $request->request->remove('ACTIONS');
        $data = $request->all();

        $data['updated_at'] = Carbon::now();
        $data['created_at'] = Carbon::now();
        $data['enabled'] = (isset($request->enabled)) ? $request->enabled : true;


        if ($type == 'store') {
            $data['created_by'] = $request->user()->id;
            $data['code'] = (isset($request->code)) ? $request->code :
                $this->generateDocNum(date('Y-m-d H:i:s'), $request->type);
        }

        return $data;
    }

    /**
     * @param $sysDate
     * @param $alias
     *
     * @return string
     */
    protected function generateDocNum($sysDate, $alias): string
    {
        $alias = Str::limit($alias, 2);

        $data_date = strtotime($sysDate);
        $year_val = date('y', $data_date);
        $month = date('m', $data_date);

        $day_val = date('j', $data_date);

        if ((int)$day_val === 1) {
            $document = Str::upper($alias) . '-' . sprintf('%05s', '1');
            $check_document = Document::where('document_number', '=', $document)->first();
            if (!$check_document) {
                return Str::upper($alias) . '-' . (int)$year_val . $month . sprintf('%05s', '1');
            } else {
                //SQ-220100001
                return $this->itemCode($data_date, $alias, $year_val, $month);
            }
        }
        return $this->itemCode($data_date, $alias, $year_val, $month);
    }

    /**
     * @param $data_date
     * @param $alias
     * @param $year_val
     * @param $month
     * @return string
     */
    protected function itemCode($data_date, $alias, $year_val, $month): string
    {
        $full_year = date('Y', $data_date);
        $end_date = date('t', $data_date);

        $first_date = "${full_year}-${month}-01";
        $second_date = "${full_year}-${month}-${end_date}";

        $doc_num = Document::selectRaw('document_number as code')
            ->whereBetween(DB::raw('CONVERT(created_at, date)'), [$first_date, $second_date])
            ->orderBy('code', 'DESC')
            ->first();

        $number = empty($doc_num) ? '0000000000' : $doc_num->code;
        $clear_doc_num = (int)substr($number, 7, 12);
        $number = $clear_doc_num + 1;

        return Str::upper($alias) . '-' . (int)$year_val . $month . sprintf('%05s', $number);
    }
}
