<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payrolls\StoreWorkLocationRequest;
use App\Http\Requests\Payrolls\UpdateWorkLocationRequest;
use App\Models\Payroll\WorkLocation;

class WorkLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Payrolls\StoreWorkLocationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWorkLocationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payroll\WorkLocation  $workLocation
     * @return \Illuminate\Http\Response
     */
    public function show(WorkLocation $workLocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Payrolls\UpdateWorkLocationRequest  $request
     * @param  \App\Models\Payroll\WorkLocation  $workLocation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWorkLocationRequest $request, WorkLocation $workLocation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payroll\WorkLocation  $workLocation
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkLocation $workLocation)
    {
        //
    }
}
