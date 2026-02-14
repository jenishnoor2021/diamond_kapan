<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Khata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AdminKhataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $khatas = Khata::orderBy('id', 'DESC')->get();
        return view('admin.khata.index', compact('khatas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.khata.create');
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
            'fname' => 'required',
            'lname' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($input)->withErrors($validator);
        }

        // Remove unwanted keys (party_rates keys) from input
        $partyData = collect($input)->only(['type', 'fname', 'lname', 'address', 'mobile', 'gst_no'])->toArray();

        Khata::create($partyData);

        return redirect('admin/khata')->with('success', "Add Record Successfully");
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
        $khata = Khata::findOrFail($id);
        return view('admin.khata.edit', compact('khata'));
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
        $khata = Khata::findOrFail($id);
        $input = $request->all();

        // Validation
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($input)->withErrors($validator);
        }

        // Remove unwanted keys (party_rates keys) from input
        $partyData = collect($input)->only(['type', 'fname', 'lname', 'address', 'mobile', 'gst_no'])->toArray();

        // Update Party Information
        $khata->update($partyData);

        return redirect('admin/khata')->with('success', 'Party updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $khata = Khata::findOrFail($id);

        $khata->incomes()->delete();
        $khata->khataBills()->delete();
        $khata->expenses()->delete();

        $khata->delete();
        return Redirect::back()->with('success', "Delete Record Successfully");
    }

    public function khataActive($id)
    {
        $khata = Khata::where('id', $id)->first();
        if ($khata->is_active == 1) {
            $khata->is_active = 0;
        } else {
            $khata->is_active = 1;
        }
        $khata->save();
        return redirect()->back()->with('success', "Update Record Successfully");
    }
}
