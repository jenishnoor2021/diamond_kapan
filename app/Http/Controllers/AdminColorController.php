<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Validator;

class AdminColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors = Color::orderBy('id', 'DESC')->get();
        return view('admin.color.index', compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.color.create');
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
            'c_name' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($input)->withErrors($validator);
        }

        Color::create($input);
        // return redirect('admin/color')->with('success', "Add Record Successfully");/
        return Redirect::back()->with('success', "Add Record Successfully");
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
        $color = Color::findOrFail($id);
        return view('admin.color.edit', compact('color'));
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
        $push = Color::findOrFail($id);

        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'c_name' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($input)->withErrors($validator);
        }

        $push->update($input);
        return redirect('admin/color')->with('success', "Update Record Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $color = Color::findOrFail($id);
        $color->delete();
        return Redirect::back()->with('success', "Delete Record Successfully");
    }
}
