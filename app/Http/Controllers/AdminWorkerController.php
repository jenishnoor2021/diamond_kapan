<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Worker;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AdminWorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workers = Worker::orderBy('id', 'DESC')->get();
        return view('admin.worker.index', compact('workers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $designations = Designation::get();
        return view('admin.worker.create', compact('designations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'fname' => [
                'required',
                'unique:workers,fname',
                'regex:/^\S+$/',
            ],
            'lname' => 'required',
            'designation' => 'required',
            // 'address' => 'required',
            // 'mobile' => 'required',
            // 'aadhar_no' => 'required',
        ], [
            'fname.regex' => 'The first name must not contain spaces.',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($input)->withErrors($validator);
        }

        // Remove unwanted keys (worker_rates keys) from input
        $workerData = collect($input)->only(['fname', 'lname', 'designation', 'address', 'mobile', 'aadhar_no', 'bank_name', 'ifsc_code', 'account_no', 'remark', 'account_holder_name'])->toArray();

        Worker::create($workerData);

        return redirect('admin/worker')->with('success', "Add Record successfully");
    }

    public function workerExists($name)
    {
        return Worker::where('fname', $name)->exists();
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
        $worker = Worker::findOrFail($id);
        $designations = Designation::get();
        return view('admin.worker.edit', compact('worker', 'designations'));
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
        $worker = Worker::findOrFail($id);

        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'fname' => [
                'required',
                'unique:workers,fname,' . $id,
                'regex:/^\S+$/', // no spaces allowed
            ],
            'lname' => 'required',
            'designation' => 'required',
            // 'address' => 'required',
            // 'mobile' => 'required',
            // 'aadhar_no' => 'required',
        ], [
            'fname.regex' => 'The first name must not contain spaces.',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($input)->withErrors($validator);
        }

        // Remove unwanted keys (party_rates keys) from input
        $workerData = collect($input)->only(['fname', 'lname', 'designation', 'address', 'mobile', 'aadhar_no', 'bank_name', 'ifsc_code', 'account_no', 'remark', 'account_holder_name'])->toArray();

        // Update Party Information
        $worker->update($workerData);

        return redirect('admin/worker')->with('success', "update Record successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $worker = Worker::findOrFail($id);
        $worker->delete();

        return Redirect::back()->with('success', "Delete Record Successfully");
    }

    public function workerActive($id)
    {
        $worker = Worker::where('id', $id)->first();
        if ($worker->is_active == 1) {
            $worker->is_active = 0;
        } else {
            $worker->is_active = 1;
        }
        $worker->save();
        return redirect()->back()->with('success', "update Record Successfully");
    }

    public function getWorkersByDesignation(Request $request)
    {
        $designation = $request->input('designation');
        $workers = Worker::where('is_active', 1)->where('designation', $designation)->get();

        return response()->json($workers);
    }
}
