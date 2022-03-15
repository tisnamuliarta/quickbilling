<?php

namespace App\Services\Documents;

use App\Models\Documents\Document;
use App\Traits\ApiResponse;

class DocumentService
{
    use ApiResponse;

    /**
     * @param $request
     * @return array
     */
    public function index($request): array
    {
        $options = $request->options;
        $pages = isset($options->page) ? (int)$options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int)$options->itemsPerPage : 20;
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

        $extra_list['details'] = [
            $this->form('document_items')
        ];
        $result['form'] = array_merge($this->form('documents'), $extra_list);
        return array_merge($result, [
            "rows" => $all_data,
        ]);
    }

    /**
     * @param $form
     * @return array
     */
    public function formData($form): array
    {
        return [
            'name' => $form['name'],
            'company_id' => session('company_id'),
            'type' => (array_key_exists('type', $form)) ? $form['type'] : null,
            'email' => (array_key_exists('email', $form)) ? $form['email'] : null,
        ];
    }
}
