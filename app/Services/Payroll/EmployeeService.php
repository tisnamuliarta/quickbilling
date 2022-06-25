<?php

namespace App\Services\Payroll;

use App\Models\Payroll\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    /**
     * @param $request
     * @return array
     */
    public function index($request): array
    {
        $type = (isset($request->type)) ? $request->type : '';
        $options = $request->options;
        $pages = isset($options->page) ? (int) $options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int) $options->itemsPerPage : 10;
        $sorts = isset($options->sortBy[0]) ? (string) $options->sortBy[0] : 'id';
        $order = isset($options->sortDesc[0]) ? (string) $options->sortDesc[0] : 'desc';
        $offset = ($pages - 1) * $row_data;

        $result = [];
        $query = Employee::select(
            'employees.*',
            DB::raw("'actions' as actions")
        )
            ->with(['workLocation', 'bank', 'entity'])
            ->where('type', 'LIKE', '%'.$type.'%');

        $result['total'] = $query->count();

        $all_data = $query->orderBy($sorts, $order)
            ->offset($offset)
            ->limit($row_data)
            ->get();

        $result['form'] = $this->getForm($type);

        return array_merge($result, [
            'rows' => $all_data,
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
        $form['price_include_tax'] = false;
        $form['type'] = $type;
        $form['issued_at'] = Carbon::now()->format('Y-m-d');
        $form['due_at'] = Carbon::now()->addDay(30)->format('Y-m-d');
        $form['payment_term_id'] = 1;
        $form['discount_type'] = 'Percent';
        $form['withholding_type'] = 'Percent';
        $form['status'] = 'draft';
        $form['tax_details'] = [];
        $form['items'] = [];
        $form['shipping_fee'] = 0;
        $form['category_id'] = 0;
        $form['parent_id'] = 0;
        $form['id'] = 0;
        $form['document_number'] = $this->generateDocNum(date('Y-m-d H:i:s'), $type);
        $form['temp_id'] = mt_rand(100000, 999999999999);

        return $form;
    }

    /**
     * @param $request
     * @param $type
     * @param  null  $id
     * @return array
     */
    public function formData($request, $type, $id = null): array
    {
        $request->mergeIfMissing([
            'entity_id' => auth()->user()->entity_id,
        ]);

        $currency = Currency::where('code', $request->default_currency_code)->first();

        $contact = Contact::where('id', $request->contact_id)->first();

        $request->request->remove('tags');
        $request->request->remove('items');
        $request->request->remove('tax_details');
        $request->request->remove('id');
        $request->request->remove('created_at');
        $request->request->remove('updated_at');
        $request->request->remove('deleted_at');
        $request->request->remove('currency');
        $request->request->remove('entity');
        $request->request->remove('parent');
        $request->request->remove('child');

        $request->merge([
            'currency_code' => $request->default_currency_code,
            'currency_rate' => $currency->rate,
            'contact_name' => $contact->name,
            'contact_email' => $contact->email,
            'contact_tax_number' => $contact->tax_number,
            'contact_phone' => $contact->phone,
            'contact_zip_code' => $contact->zip_code,
            'contact_city' => $contact->city,
        ]);
        $request->request->remove('default_currency_code');
        $request->request->remove('default_currency_symbol');
        $data = $request->all();

        if ($type == 'store') {
            $data['created_by'] = $request->user()->id;
            $data['document_number'] = $this->generateDocNum(date('Y-m-d H:i:s'), $request->type);
            $data['status'] = 'open';
        }

        return $data;
    }
}
