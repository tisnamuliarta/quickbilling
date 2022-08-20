<?php

namespace App\Services\Financial;

use App\Traits\Accounting;
use App\Traits\Categories;
use IFRS\Models\Account;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AccountService
{
    use Categories;
    use Accounting;

    /**
     * @param $request
     *
     * @return mixed
     */
    public function index($request)
    {
        $row_data = isset($request->itemsPerPage) ? (int)$request->itemsPerPage : 150;
        $sorts = isset($request->sortBy[0]) ? (string)$request->sortBy[0] : 'code';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';
        $search = isset($request->search) ? (string)$request->search : '';
        $zero_balance = (isset($request->show_zero_balance)) ? $request->show_zero_balance : null;

        $query = Account::where(DB::raw("CONCAT(name, ' ', account_type)"), 'LIKE', '%' . $search . '%')
            ->orWhere(function ($query) use ($search) {
                $query->whereHas('category', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                });
            })
            ->with(['currency', 'category'])
            ->orderBy($sorts, $order)
            ->get()
            //->paginate($row_data)
            ->filter(function ($user) use ($zero_balance) {
                if ($zero_balance == 'No') {
                    return $user->balance != 0;
                }
                return $user;
            });

        return [
            'data' => collect($query)->values()
        ];
    }

    /**
     * @param $type
     *
     * @return array
     */
    public function dataByType($type): array
    {
        if ($type == '') {
            $query = Account::selectRaw(
                " CONCAT('(', code, ') ', name, ' (', account_type, ')') as name, id "
            )
                ->orderBy('code')
                ->where('account_type', 'LIKE', '%' . $type . '%')
                ->get();
        } else {
            $type = explode(', ', $type);
            $query = Account::selectRaw(
                " CONCAT('(', code, ') ', name, ' (', account_type, ')') as name, id, code "
            )
                ->orderBy('code')
                ->whereIn('account_type', $type)->get();
        }


        return [
            'data' => $query,
        ];
    }

    /**
     * @param $request
     *
     * @return array
     */
    public function formData($request): array
    {
        $data = $request->all();
        Arr::forget($data, 'updated_at');
        Arr::forget($data, 'created_at');
        Arr::forget($data, 'deleted_at');
        Arr::forget($data, 'destroyed_at');
        Arr::forget($data, 'account_type_list');
        Arr::forget($data, 'default_currency_code');
        Arr::forget($data, 'default_currency_symbol');
        Arr::forget($data, 'id');
        Arr::forget($data, 'code');
        Arr::forget($data, 'currency');
        Arr::forget($data, 'entity');
        Arr::forget($data, 'category');
        Arr::forget($data, 'balances');
        Arr::forget($data, 'balance');

        return $data;
    }

    /**
     * create new account
     *
     * @param string $name
     * @param string $accountType
     * @param int $subType
     *
     * @return mixed
     * @throws \Exception
     */
    public function createAccount(string $name, string $accountType, int $subType): mixed
    {
        try {
            return Account::updateOrCreate([
                'entity_id' => auth()->user()->entity->id,
                'currency_id' => auth()->user()->entity->currency_id,
                'account_type' => $accountType,
                'category_id' => $subType,
                'name' => $name,
            ]);
        } catch (\Exception $e) {
            return throw new \Exception($e->getMessage());
        }
    }
}
