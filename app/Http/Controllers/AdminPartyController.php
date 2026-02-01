<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AdminPartyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partys = Party::orderBy('id', 'DESC')->get();
        return view('admin.party.index', compact('partys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.party.create');
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
            // 'party_code' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($input)->withErrors($validator);
        }

        // Remove unwanted keys (party_rates keys) from input
        $partyData = collect($input)->only(['type', 'fname', 'lname', 'party_code', 'address', 'mobile', 'gst_no'])->toArray();

        Party::create($partyData);

        return redirect('admin/party')->with('success', "Add Record Successfully");
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
        $party = Party::findOrFail($id);
        return view('admin.party.edit', compact('party'));
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
        $party = Party::findOrFail($id);
        $input = $request->all();

        // Validation
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            // 'party_code' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($input)->withErrors($validator);
        }

        // Remove unwanted keys (party_rates keys) from input
        $partyData = collect($input)->only(['type', 'fname', 'lname', 'party_code', 'address', 'mobile', 'gst_no'])->toArray();

        // Update Party Information
        $party->update($partyData);

        return redirect('admin/party')->with('success', 'Party updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $party = Party::findOrFail($id);
        $party->delete();
        return Redirect::back()->with('success', "Delete Record Successfully");
    }

    public function partyActive($id)
    {
        $party = Party::where('id', $id)->first();
        if ($party->is_active == 1) {
            $party->is_active = 0;
        } else {
            $party->is_active = 1;
        }
        $party->save();
        return redirect()->back()->with('success', "Update Record Successfully");
    }
}
