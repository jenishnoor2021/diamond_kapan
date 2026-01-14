<?php

namespace App\Http\Controllers;

use App\Models\KhataBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AdminKhataBillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'khatas_id' => 'required',
            'bill_no' => 'required',
            'bill_date' => 'required|date',
            'amount' => 'required|numeric'
        ]);

        KhataBill::create($request->all());

        return redirect()->back()->with('success', 'Bill added successfully');
    }

    public function edit($id)
    {
        return KhataBill::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bill_no' => 'required',
            'bill_date' => 'required|date',
            'amount' => 'required|numeric'
        ]);

        KhataBill::findOrFail($id)->update($request->all());

        return redirect()->back()->with('success', 'Bill updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
