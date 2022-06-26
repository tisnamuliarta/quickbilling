<?php

namespace App\Http\Controllers\BusinessPartner;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessPartner\StoreContactRequest;
use App\Models\Inventory\Contact;
use App\Models\Inventory\ContactBank;
use App\Models\Inventory\ContactEmail;
use App\Services\Inventory\ContactService;
use App\Traits\ContactDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    use ContactDetail;

    public $service;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
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
        $result = array_merge($result, $this->service->index($request, $type));

        return $this->success($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreContactRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreContactRequest $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $contact = Contact::create($this->service->formData($request, 'store'));

            $this->processDetails($request, $contact);

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
     * @param $request
     * @param $contact
     * @return void
     */
    protected function processDetails($request, $contact)
    {
        if ($request->banks) {
            $this->storeContactBank($request->banks, $contact['id']);
        }

        if ($request->emails) {
            $this->storeContactEmail($request->emails, $contact['id']);
        }

        if ($request->can_login) {
            $this->createUser($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $data = Contact::where('id', '=', $id)->first();
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
        $form = array_merge($this->form('contacts'), $extra_list);

        return $this->success([
            'rows' => $data,
            'form' => $form,
            'count' => ($data) ? 1 : 0,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreContactRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(StoreContactRequest $request, int $id)
    {
        DB::transaction();
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
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
     * @return void
     */
    public function deleteBank($id)
    {
        ContactBank::where('id', $id)->delete();
    }

    /**
     * @param $id
     * @return void
     */
    public function deleteEmail($id)
    {
        ContactEmail::where('email', $id)->delete();
    }
}
