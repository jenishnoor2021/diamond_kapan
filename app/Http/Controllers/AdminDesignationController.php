<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Worker;
use App\Models\Process;
use App\Models\WorkerRate;
use App\Models\Designation;
use App\Models\Workerrange;
use Illuminate\Http\Request;
use App\Models\DesignationWiseRate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AdminDesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designations = Designation::orderBy('id', 'DESC')->get();
        return view('admin.designation.index', compact('designations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'unique:designations,name',
                'regex:/^\S+$/',
            ],
            'category' => 'required',
        ], [
            'name.regex' => 'The name must not contain spaces.',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        Designation::create($request->all());

        return redirect('admin/designation')->with('success', "Add Record Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roundworkerrangs = Workerrange::where('shape', 'Round')->get();
        $otherworkerrangs = Workerrange::where('shape', 'Other')->get();
        $designationWiseRate = DesignationWiseRate::where('designation_id', $id)->pluck('value', 'range_key');
        $designation = Designation::findOrFail($id);
        return view('admin.designation.edit', compact('designation', 'roundworkerrangs', 'otherworkerrangs', 'designationWiseRate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'unique:designations,name,' . $id,
                'regex:/^\S+$/',
            ],
            'category' => 'required',
        ], [
            'name.regex' => 'The name must not contain spaces.',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $designation = Designation::findOrFail($id);

        $process = Process::where('designation', $designation->name)->get();

        foreach ($process as $proces) {
            $proces->update(['designation' => $request->name]);
        }

        $workers = Worker::where('designation', $designation->name)->get();

        foreach ($workers as $worker) {
            $worker->update(['designation' => $request->name]);
        }

        $designationData = collect($input)->only(['category', 'name', 'rate_apply_on'])->toArray();

        $designation->update($designationData);

        DB::table('designation_wise_rates')->where('designation_id', $id)->delete();

        $designationWiseRatesData = [];

        $roundWorkerRanges = Workerrange::where('shape', 'Round')->get();
        foreach ($roundWorkerRanges as $roundWorkerRange) {
            $key = $roundWorkerRange->key;
            $value = $input[$key] ?? $roundWorkerRange->value; // Default to '1' if not provided

            $designationWiseRatesData[] = [
                'designation_id' => $id,
                'range_key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $otherWorkersRanges = Workerrange::where('shape', 'Other')->get();
        foreach ($otherWorkersRanges as $otherWorkersRange) {
            $key = $otherWorkersRange->key;
            $value = $input[$key] ?? $otherWorkersRange->value; // Default to '1' if not provided

            $designationWiseRatesData[] = [
                'designation_id' => $id,
                'range_key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($designationWiseRatesData)) {
            DB::table('designation_wise_rates')->insert($designationWiseRatesData);
        }

        return redirect('admin/designation')->with('success', "Update Record Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $designation = Designation::findOrFail($id);

        // $process = Process::where('designation', $designation->name)->get();

        // foreach ($process as $proces) {
        //     $proces->delete();
        // }

        // $workers = Worker::where('designation', $designation->name)->get();

        // foreach ($workers as $worker) {
        //     $worker->delete();
        // }

        DB::table('designation_wise_rates')->where('designation_id', $id)->delete();

        $designation->delete();

        return Redirect::back()->with('success', "Delete Record Successfully");
    }
}
