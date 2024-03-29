<?php

namespace App\Http\Controllers\BusinessPartner;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessPartner\StoreContactRequest;
use App\Models\Financial\Category;
use App\Models\Inventory\Contact;
use App\Models\Inventory\ContactBank;
use App\Models\Inventory\ContactEmail;
use App\Services\Financial\AccountService;
use App\Services\Inventory\ContactService;
use App\Traits\ContactDetail;
use App\Traits\Financial;
use IFRS\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    use ContactDetail;
    use Financial;

    public ContactService $service;

    /**
     * MasterUserController constructor.
     */
    public function __construct(ContactService $service)
    {
        $this->service = $service;
        //        $this->middleware(['direct_permission:Roles-index'])->only(['index', 'show', 'permissionRole']);
        //        $this->middleware(['direct_permission:Roles-store'])->only(['store', 'storePermissionRole']);
        //        $this->middleware(['direct_permission:Roles-edits'])->only('update');
        //        $this->middleware(['direct_permission:Roles-erase'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $type = isset($request->type) ? (string)$request->type : 'index';
        $result = [];
        $extra_list['emails'] = [
            [
                'email' => null,
            ],
        ];
        $extra_list['password'] = null;
        $extra_list['banks'] = [
            [
                'name' => null,
                'branch' => null,
                'contact_account_name' => null,
                'contact_account_number' => null,
            ],
        ];
        $result['form'] = array_merge($this->form('contacts'), $extra_list);
        $collection = collect($this->service->index($request, $type));
        $result = $collection->merge($result);

        return $this->success($result->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreContactRequest $request
     *
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function store(StoreContactRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $contact = Contact::create($this->service->formData($request, 'store'));

            $this->processDetails($request, $contact);

            // $accountService = new AccountService();
            // $accountType = ($request->type == 'Customer') ? 'RECEIVABLE' : 'PAYABLE';
            // $accountCategory = Category::where('category_type', $accountType)->first();

            // $accountService->createAccount($request->name, $accountType, $accountCategory->id);

            DB::commit();

            return $this->success([
                'errors' => false,
            ], 'Data inserted!');
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->error($exception->getMessage(), 422, [
                'errors' => true,
                'Trace' => $exception->getTrace(),
            ]);
        }
    }

    /**
     * It stores the contact's bank details and emails, and if the contact can login, it creates a user
     *
     * @param request The request object
     * @param contact The contact array returned from the storeContact method.
     *
     * @throws \Exception
     */
    protected function processDetails($request, $contact)
    {
        $accountService = new AccountService();
        // $accountType = ($request->type == 'Customer') ? 'RECEIVABLE' : 'PAYABLE';
        $accountCategory1 = Category::where('category_type', 'RECEIVABLE')->first();
        $accountCategory2 = Category::where('category_type', 'PAYABLE')->first();

        $receiveable = $accountService->createAccount($request->name, 'RECEIVABLE', $accountCategory1->id);
        $payable = $accountService->createAccount($request->name, 'PAYABLE', $accountCategory2->id);

        if (!empty($request->banks)) {
            $this->storeContactBank($request->banks, $contact['id']);
        }

        if (!empty($request->emails)) {
            $this->storeContactEmail($request->emails, $contact['id']);
        }

        if (!empty($request->can_login)) {
            $this->createUser($request->all());
        }
        $contact = Contact::find($contact['id']);
        if (!$contact->receivable_account_id) {
            $contact->receivable_account_id = $this->getAccountIdByName($request->name, 'RECEIVABLE');
        }
        if (!$contact->payable_account_id) {
            $contact->payable_account_id = $this->getAccountIdByName($request->name, 'PAYABLE');
        }
        $contact->save();
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(Request $request, $id): JsonResponse
    {
        $data = Contact::where('id', '=', $id)
            ->with(['banks', 'emails', 'sellAccount.balances', 'purchaseAccount.balances'])
            ->first();

        $start_date = $request->date_from;
        $end_date = $request->date_to;
        if ($data->type == 'Customer') {
            $account = Account::find($data->receivable_account_id);
        } else {
            $account = Account::find($data->payable_account_id);
        }

        $extra_list['emails'] = [
            [
                'email' => null,
            ],
        ];
        $extra_list['password'] = null;
        $extra_list['banks'] = [
            [
                'name' => null,
                'branch' => null,
                'contact_account_name' => null,
                'contact_account_number' => null,
            ],
        ];

        $collect = $account->getTransactions($start_date, $end_date);
        $form = array_merge($this->form('contacts'), $extra_list);

        $result = array_merge(['form' => $form], ['data' => $data], $collect);

        return $this->success($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreContactRequest $request
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function update(StoreContactRequest $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $formData = $this->service->formData($request, 'update');
            $contact = Contact::find($id);
            foreach ($formData as $index => $formDatum) {
                $contact->$index = $formDatum;
            }
            $contact->save();

            $this->processDetails($request, $contact);

            DB::commit();

            return $this->success([
                'errors' => false,
            ], 'Data updated!');
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->error($exception->getMessage(), 422, [
                'errors' => true,
                'Trace' => $exception->getTrace(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $details = Contact::where('id', '=', $id)->first();
        if ($details) {
            Contact::where('id', '=', $id)->delete();

            return $this->success([
                'errors' => false,
            ], 'Row deleted!');
        }

        return $this->error('Row not found', 422, [
            'errors' => true,
        ]);
    }

    /**
     * @param $id
     *
     * @return void
     */
    public function deleteBank($id)
    {
        ContactBank::where('id', $id)->delete();
    }

    /**
     * @param $id
     *
     * @return void
     */
    public function deleteEmail($id)
    {
        ContactEmail::where('email', $id)->delete();
    }
}
