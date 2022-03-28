<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Contact;
use App\Models\Inventory\ContactBank;
use App\Models\Inventory\ContactEmail;
use App\Services\Inventory\ContactService;
use App\Traits\ContactDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
                'email' => null
            ]
        ];
        $extra_list['password'] = null;
        $extra_list['banks'] = [
            [
                'name' => null,
                'branch' => null,
                'contact_account_name' => null,
                'contact_account_number' => null,
            ]
        ];
        $result['form'] = array_merge($this->form('contacts'), $extra_list);
        $result = array_merge($result, $this->service->index($request));

        return $this->success($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($this->validation($request)) {
            return $this->error($this->validation($request), 422, [
                "errors" => true
            ]);
        }
        DB::beginTransaction();
        $form = $request->form;
        try {
            $contact = Contact::create($this->service->formData($form));

            $this->processDetails($form, $contact);

            DB::commit();
            return $this->success([
                "errors" => false
            ], 'Data inserted!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->error($exception->getMessage(), 422, [
                "errors" => true,
                "Trace" => $exception->getTrace()
            ]);
        }
    }

    /**
     * @param $request
     * @return false|string
     */
    protected function validation($request)
    {
        $messages = [
            'form.name' => 'Name is required!',
        ];

        $validator = Validator::make($request->all(), [
            'form.name' => 'required',
        ], $messages);

        $string_data = "";
        if ($validator->fails()) {
            foreach (collect($validator->messages()) as $error) {
                foreach ($error as $items) {
                    $string_data .= $items . " \n  ";
                }
            }
            return $string_data;
        } else {
            return false;
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
        $data = Contact::where("id", "=", $id)->get();

        return $this->success([
            'rows' => $data
        ]);
    }

    /**
     * @param $form
     * @param $contact
     * @return void
     */
    protected function processDetails($form, $contact)
    {
        if ($form['banks']) {
            $this->storeContactBank($form['banks'], $contact['id']);
        }

        if ($form['emails']) {
            $this->storeContactEmail($form['emails'], $contact['id']);
        }

        if ($form['can_login']) {
            $this->createUser($form);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if ($this->validation($request)) {
            return $this->error($this->validation($request), 422, [
                "errors" => true
            ]);
        }

        $form = $request->form;
        try {
            $formData = $this->service->formData($form);
            $contact = Contact::find($id);
            foreach ($formData as $index => $formDatum) {
                $contact->$index = $formDatum;
            }
            $contact->save();

            $this->processDetails($form, $contact);

            DB::commit();

            return $this->success([
                "errors" => false
            ], 'Data updated!');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422, [
                "errors" => true,
                "Trace" => $exception->getTrace()
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
        $details = Contact::where("id", "=", $id)->first();
        if ($details) {
            Contact::where("id", "=", $id)->delete();
            return $this->success([
                "errors" => false
            ], 'Row deleted!');
        }

        return $this->error('Row not found', 422, [
            "errors" => true
        ]);
    }

    public function deleteBank($id)
    {
        ContactBank::where('id', $id)->delete();
    }

    public function deleteEmail($id)
    {
        ContactEmail::where('email', $id)->delete();
    }
}
