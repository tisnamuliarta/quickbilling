<?php

namespace App\Http\Controllers\BusinessPartner;

use App\Http\Controllers\Controller;
use App\Models\Inventory\ContactEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $type = $request->type;
        $data = ContactEmail::select('id', 'email')->get();
        if (count($data) < 1) {
            $data = [
                [
                    'id' => null,
                    'email' => null,
                ],
            ];
        }
        $header = ['id', 'Email'];

        $simple = ContactEmail::pluck('email');

        return $this->success([
            'rows' => $data,
            'header' => $header,
            'simple' => $simple,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $details = collect($request->details);
        $contact_id = $request->contact_id;
        DB::beginTransaction();
        try {
            foreach ($details as $detail) {
                if (empty($detail['email'])) {
                    return $this->error('Email cannot empty', '422');
                }

                $data = ContactEmail::where('id', '=', $detail['id'])->first();
                if ($data) {
                    $data->name = $detail['email'];
                    $data->updated_at = Carbon::now();
                } else {
                    $data = new ContactEmail();
                    $data->name = $detail['email'];
                    $data->created_at = Carbon::now();
                }
                $data->save();
            }
            DB::commit();

            return $this->success([], 'Rows updated!');
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->error($exception->getMessage(), '422');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            ContactEmail::whereIn('id', $id)->delete();
            DB::commit();

            return $this->success([], 'Data updated!');
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->error($exception->getMessage(), '422');
        }
    }
}
