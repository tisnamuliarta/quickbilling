<?php

namespace App\Services\Payroll;

use App\Models\Payroll\Loan;
use Illuminate\Support\Arr;

class LoanService
{
    /**
     * @param $request
     *
     * @return array
     */
    public function index($request)
    {
        $type = (isset($request->type)) ? $request->type : '';
        $row_data = isset($request->itemsPerPage) ? (int)$request->itemsPerPage : 10;
        $sorts = isset($request->sortBy[0]) ? (string)$request->sortBy[0] : 'id';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';
        $search = (isset($request->search)) ? $request->search : '';

        $query = Loan::where(function ($query) use ($search) {
            $query->whereHas('employee', function ($query) use ($search) {
                $query->where('first_name', 'LIKE', '%' . $search . '%');
            });
        })
            ->with(['employee'])
            ->orderBy($sorts, $order)
            ->paginate($row_data);

        return collect($query);
    }

    /**
     * @param $request
     * @param $type
     *
     * @return array
     */
    public function formData($request, $type): array
    {
        $data = $request->all();

        Arr::forget($data, 'default_currency_code');
        Arr::forget($data, 'default_currency_symbol');
        Arr::forget($data, 'actions');
        Arr::forget($data, 'work_location');
        Arr::forget($data, 'bank');
        Arr::forget($data, 'entity');
        Arr::forget($data, 'entity_id');
        Arr::forget($data, 'deleted_at');
        Arr::forget($data, 'updated_at');
        Arr::forget($data, 'created_at');
        Arr::forget($data, 'employee');
        Arr::forget($data, 'id');

        if ($type == 'store') {
            $data['created_by'] = auth()->user()->id;
        }

        return $data;
    }
}
