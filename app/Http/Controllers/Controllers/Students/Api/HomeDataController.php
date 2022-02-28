<?php

namespace App\Http\Controllers\Students\Api;

use App\Http\Controllers\Controller;
use App\Models\Student\HomeData;
use App\Models\Student\Student;
use App\Traits\StudentHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeDataController extends Controller
{
    use StudentHelper;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $ppdb = $this->ppdbThisYear();
        $count_pending = $this->copyQuery($ppdb)->where("approval_step", "=", "P")->count();
        $count_reregist = $this->copyQuery($ppdb)->where("approval_step", "=", "G")->count();
        $count_reject = $this->copyQuery($ppdb)->where("approval_step", "=", "R")->count();
        $count_approve = $this->copyQuery($ppdb)->where("approval_step", "=", "A")->count();

        return response()->json([
            "rows" => [
                [
                    "text" => "Daftar Ulang",
                    'value' => $count_reregist,
                ],
                [
                    "text" => "Pending",
                    'value' => $count_pending,
                ],
                [
                    "text" => "DiTolak",
                    'value' => $count_reject,
                ],
                [
                    "text" => "Diterima",
                    'value' => $count_approve,
                ]
            ],
            "ppdb" => $ppdb->start_year . "/" . $ppdb->end_year
        ]);
    }

    /**
     * @return mixed
     */
    protected function copyQuery($ppdb)
    {
        return Student::where("ppdb_id", "=", $ppdb->id);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function details(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'rows' => HomeData::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $content = HomeData::first();
            if ($content) {
                $data = HomeData::where("id", "=", $content->id)->first();
                $data->description = $request->description;
                $data->updated_at = Carbon::now();
                $data->save();

                return response()->json([
                    "errors" => false,
                    "message" => "Data updated!"
                ]);
            } else {
                $data = new HomeData();
                $data->title = 'header';
                $data->description = $request->description;
                $data->created_at = Carbon::now();
                $data->save();

                return response()->json([
                    "errors" => false,
                    "message" => "Data inserted!"
                ]);
            }

        } catch (\Exception $exception) {
            return response()->json([
                "errors" => true,
                "message" => $exception->getMessage(),
                "Trace" => $exception->getTrace()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = HomeData::where("id", "=", $id)->first();
            $data->description = $request->description;
            $data->updated_at = Carbon::now();
            $data->save();

            return response()->json([
                "errors" => false,
                "message" => "Data updated!"
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                "errors" => true,
                "message" => $exception->getMessage(),
                "Trace" => $exception->getTrace()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
