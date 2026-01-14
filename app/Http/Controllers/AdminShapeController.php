<?php

namespace App\Http\Controllers;

use App\Models\Shape;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Validator;

class AdminShapeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shapes = Shape::orderBy('id', 'DESC')->get();
        return view('admin.shape.index', compact('shapes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.shape.create');
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
            'shape_type' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($input)->withErrors($validator);
        }

        Shape::create($input);
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
        $shape = Shape::findOrFail($id);
        return view('admin.shape.edit', compact('shape'));
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
        $push = Shape::findOrFail($id);

        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'shape_type' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($input)->withErrors($validator);
        }

        $push->update($input);
        return redirect('admin/shape')->with('success', "Update Record Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shape = Shape::findOrFail($id);
        $shape->delete();
        return Redirect::back()->with('success', "Delete Record Successfully");
    }
}
