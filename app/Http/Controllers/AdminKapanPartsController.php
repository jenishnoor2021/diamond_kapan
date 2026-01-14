<?php

namespace App\Http\Controllers;

use App\Models\Kapan;
use App\Models\KapanPart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminKapanPartsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kapans = Kapan::where('is_active', 1)->orderBy('id', 'DESC')->get();
        $kapan_parts = [];
        $selectedKapan = null;
        return view('admin.kapan_parts.index', compact('kapans', 'kapan_parts', 'selectedKapan'));
    }


    public function getKapanParts(Request $request)
    {
        if ($request->filled('kapans_id')) {

            $request->validate([
                'kapans_id' => 'exists:kapans,id'
            ]);

            $selectedKapan = Kapan::find($request->kapans_id);

            $kapan_parts = DB::table('kapan_parts')
                ->where('kapans_id', $request->kapans_id)
                ->orderBy('part_no')
                ->get();
        } else {
            $selectedKapan = null;
            $kapan_parts = collect();
        }

        $kapans = Kapan::where('is_active', 1)
            ->orderBy('id', 'DESC')
            ->get();

        return view('admin.kapan_parts.index', compact(
            'kapans',
            'kapan_parts',
            'selectedKapan'
        ));
    }


    public function updateSingleWeight(Request $request)
    {
        $request->validate([
            'part_id' => 'required|integer',
            'weight'  => 'required|numeric|min:0',
        ]);

        $part = DB::table('kapan_parts')->where('id', $request->part_id)->first();

        if (!$part) {
            return response()->json(['success' => false, 'message' => 'Part not found']);
        }

        $kapan = Kapan::find($part->kapans_id);

        // total weight except current part
        $otherTotal = DB::table('kapan_parts')
            ->where('kapans_id', $part->kapans_id)
            ->where('id', '!=', $part->id)
            ->sum('weight');

        $newTotal = $otherTotal + $request->weight;

        if ($newTotal > $kapan->kapan_weight) {
            return response()->json([
                'success' => false,
                'message' => 'Total parts weight cannot be greater than Kapan Weight (' . $kapan->kapan_weight . ')'
            ]);
        }

        DB::table('kapan_parts')
            ->where('id', $request->part_id)
            ->update([
                'weight' => $request->weight,
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Weight updated successfully',
            'total_weight' => $newTotal
        ]);
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
        //
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
        //
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
        //
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
