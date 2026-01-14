<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Kapan;
use App\Models\KapanPart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AdminKapanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kapans = Kapan::orderBy('id', 'DESC')->get();
        return view('admin.kapan.index', compact('kapans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.kapan.create');
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

        $validator = Validator::make($input, [
            'kapan_name' => 'required|string|max:255|unique:kapans,kapan_name',
            'kapan_weight' => 'required|numeric',
            'kapan_quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($input)->withErrors($validator);
        }

        $kapan = Kapan::create([
            'kapan_name'     => $input['kapan_name'],
            'kapan_weight'   => $input['kapan_weight'],
            'kapan_quantity' => $input['kapan_quantity'],
        ]);

        $kapans_id      = $kapan->id;
        $kapans_name    = $kapan->kapan_name;
        $kapanQuantity  = $input['kapan_quantity'];

        $kapanPartsData = [];

        for ($i = 1; $i <= $kapanQuantity; $i++) {
            $kapanPartsData[] = [
                'kapans_id' => $kapans_id,
                'name'      => $kapans_name . '-' . $i,
                'weight'    => 0,
                'part_no'   => $i,
            ];
        }

        if (!empty($kapanPartsData)) {
            DB::table('kapan_parts')->insert($kapanPartsData);
        }

        return redirect('admin/kapan')->with('success', "Add Record Successfully");
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
        $kapan = Kapan::findOrFail($id);
        return view('admin.kapan.edit', compact('kapan'));
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
        $kapan = Kapan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kapan_name'      => 'required|string|max:255|unique:kapans,kapan_name,' . $id,
            'kapan_weight'    => 'required|numeric',
            'kapan_quantity'  => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $oldQuantity = $kapan->kapan_quantity;
        $newQuantity = $request->kapan_quantity;

        if ($newQuantity < $oldQuantity) {
            return Redirect::back()->with('error', 'New inserted quntity smaller than previous quntity');
        }

        // Update Kapan main record
        $kapan->update([
            'kapan_name'     => $request->kapan_name,
            'kapan_weight'   => $request->kapan_weight,
            'kapan_quantity' => $newQuantity,
        ]);

        // If quantity increased -> Add new parts
        if ($newQuantity > $oldQuantity) {

            $partsToAdd = $newQuantity - $oldQuantity;
            $lastPartNo = DB::table('kapan_parts')
                ->where('kapans_id', $id)
                ->max('part_no');

            $data = [];

            for ($i = 1; $i <= $partsToAdd; $i++) {
                $next = $lastPartNo + $i;

                $data[] = [
                    'kapans_id' => $id,
                    'name'      => $kapan->kapan_name . '-' . $next,
                    'weight'    => 0,
                    'part_no'   => $next,
                ];
            }

            DB::table('kapan_parts')->insert($data);
        }

        // if ($newQuantity < $oldQuantity) {
        //     DB::table('kapan_parts')
        //         ->where('kapans_id', $id)
        //         ->where('part_no', '>', $newQuantity)
        //         ->delete();
        // }

        return redirect('admin/kapan')->with('success', 'Record updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kapan = Kapan::findOrFail($id);

        KapanPart::where('kapans_id', $id)->delete();

        $kapan->delete();

        return Redirect::back()->with('success', "Delete Record Successfully");
    }

    public function statusUpdate(Request $request)
    {
        $kapan = Kapan::findOrFail($request->id);

        if ($kapan) {
            $kapan->is_active = !$kapan->is_active; // Toggle the status
            $kapan->save();

            return response()->json([
                'success' => true,
                'status' => $kapan->is_active ? 'Show' : 'Hide'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Id not found!']);
    }
}
