<?php

namespace App\Http\Controllers;

use App\Models\Kapan;
use App\Models\Diamond;
use Illuminate\Http\Request;

class AdminDiamondController extends Controller
{

    public function getOrCreateDiamond(Request $request)
    {
        $kapanId = $request->kapan_id;
        $kapanPartId = $request->kapan_part_id;

        $diamonds = Diamond::where('kapan_id', $kapanId)
            ->where('kapan_parts_id', $kapanPartId)
            ->get();

        // 🔹 If no entry exists → create default
        if ($diamonds->count() == 0) {
            Diamond::create([
                'kapan_id' => $kapanId,
                'kapan_parts_id' => $kapanPartId,
                'diamond_name' => 'A',
                'weight' => 0.00,
                'prediction_weight' => 0.00,
            ]);

            $diamonds = Diamond::where('kapan_id', $kapanId)
                ->where('kapan_parts_id', $kapanPartId)
                ->get();
        }

        return response()->json($diamonds);
    }

    public function saveDiamond(Request $request)
    {
        Diamond::create([
            'kapan_id' => $request->kapan_id,
            'kapan_parts_id' => $request->kapan_part_id,
            'diamond_name' => $request->diamond_name,
            'weight' => $request->weight,
            'prediction_weight' => $request->prediction_weight,
        ]);

        return response()->json(['success' => true]);
    }


    public function updateDiamond(Request $request)
    {
        Diamond::where('id', $request->id)->update([
            'weight' => $request->weight,
            'prediction_weight' => $request->prediction_weight,
        ]);

        return response()->json(['success' => true]);
    }

    public function index()
    {
        $kapans = Kapan::where('is_active', 1)->orderBy('id', 'DESC')->get();
        return view('admin.issue.return', compact('kapans'));
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
