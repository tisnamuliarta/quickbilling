<?php

namespace App\Services\Financial;

use App\Traits\Accounting;
use App\Traits\Categories;
use IFRS\Models\Account;
use Illuminate\Support\Arr;
use JetBrains\PhpStorm\ArrayShape;

class AccountService
{
    use Categories;
    use Accounting;

    /**
     * @param $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index($request): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $row_data = isset($request->itemsPerPage) ? (int) $request->itemsPerPage : 150;
        $sorts = isset($request->sortBy[0]) ? (string) $request->sortBy[0] : 'code';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';
        $search = isset($request->search) ? (string) $request->search : '';

        $query = Account::with(['currency', 'category', 'balances'])
            ->where('name', 'LIKE', '%'.$search.'%')
            ->orderBy($sorts, $order)
            ->paginate($row_data);

        return $query;
    }

    /**
     * @param $type
     * @return array
     */
    #[ArrayShape(['data' => 'mixed'])]
    public function dataByType($type): array
    {
        $query = Account::selectRaw(
            " CONCAT('(', code, ') ', name, ' (', account_type, ')') as name, id "
        )
            ->where('account_type', 'LIKE', '%'.$type.'%')
            ->orderBy('code')
            ->get();

        return [
            'data' => $query,
        ];
    }

    /**
     * @param $request
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

        return $data;
    }

    /**
     * create new account
     *
     * @param  string  $name
     * @param  string  $accountType
     * @param  int  $subType
     * @return int
     *
     * @throws \Exception
     */
    public function createAccount(string $name, string $accountType, int $subType)
    {
        try {
            Account::updateOrCreate([
                'name' => $name,
                'entity_id' => auth()->user()->entity->id,
                'currency_id' => auth()->user()->entity->currency_id,
                'account_type' => $accountType,
                'category_id' => $subType,
            ]);

            return 0;
        } catch (\Exception $e) {
            return throw new \Exception($e->getMessage());
        }
    }
}
