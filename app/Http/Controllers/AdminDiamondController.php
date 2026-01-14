<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Sell;
use App\Models\Color;
use App\Models\Issue;
use App\Models\Kapan;
use App\Models\Party;
use App\Models\Shape;
use App\Models\Polish;
use App\Models\Clarity;
use App\Models\Diamond;
use App\Models\Purchase;
use App\Models\Symmetry;
use App\Models\KapanPart;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PurchaseDiamondsExport;
use Illuminate\Support\Facades\Redirect;

class AdminDiamondController extends Controller
{
    public function subDivision(Request $request)
    {
        $kapans = Kapan::where('is_active', 1)->orderBy('id', 'DESC')->get();
        $shapes = Shape::get();
        $kapan_parts = [];
        $diamonds = [];

        $kapanPart = null;
        $usedWeight = 0;
        $remainingWeight = 0;
        $totalPWeight = 0;

        if ($request->kapans_id && $request->kapan_parts_id) {

            $diamonds = Diamond::where('kapans_id', $request->kapans_id)
                ->where('kapan_parts_id', $request->kapan_parts_id)
                ->get();

            $kapan_parts = DB::table('kapan_parts as kp')
                ->leftJoin(DB::raw('
                    (SELECT kapan_parts_id, COUNT(*) as diamond_count
                        FROM diamonds
                        GROUP BY kapan_parts_id) d
                '), 'kp.id', '=', 'd.kapan_parts_id')
                ->where('kp.kapans_id', $request->kapans_id)
                ->select(
                    'kp.*',
                    DB::raw('COALESCE(d.diamond_count, 0) as diamond_count')
                )
                ->orderBy('diamond_count', 'ASC') // zero diamonds first
                ->get();

            // Selected Kapan Part
            $kapanPart = DB::table('kapan_parts')
                ->where('id', $request->kapan_parts_id)
                ->first();

            // Used Weight
            // $usedWeight = $diamonds->sum('weight');

            // total pridication Weight
            $totalPWeight = $diamonds->sum('prediction_weight');

            // Remaining Weight
            // if ($kapanPart) {
            //     $remainingWeight = $kapanPart->weight - $usedWeight;
            // }

            if ($kapanPart) {
                $remainingWeight = $kapanPart->weight - $totalPWeight;
            }
        }

        return view('admin.diamond.sub_division', compact('kapans', 'diamonds', 'kapan_parts', 'kapanPart', 'usedWeight', 'totalPWeight', 'remainingWeight', 'shapes'));
    }

    public function getKapanParts(Request $request)
    {
        $kapanId = $request->kapans_id;

        $parts = DB::table('kapan_parts')
            ->leftJoin('diamonds', 'kapan_parts.id', '=', 'diamonds.kapan_parts_id')
            ->where('kapan_parts.kapans_id', $kapanId)
            ->select(
                'kapan_parts.id',
                'kapan_parts.name',
                DB::raw('COUNT(diamonds.id) as diamond_count')
            )
            ->groupBy('kapan_parts.id', 'kapan_parts.name')
            ->orderBy('diamond_count', 'asc')
            ->get();

        return response()->json($parts);
    }

    public function store(Request $request)
    {
        $kapanPart = DB::table('kapan_parts')->where('id', $request->kapan_parts_id)->first();

        if (!$kapanPart) {
            return back()->with('error', 'Invalid Kapan Part');
        }

        $finalDiamondName = $kapanPart->name . '-' . $request->diamond_name;

        $request->validate([
            'diamond_name' => [
                'required',
                Rule::unique('diamonds', 'diamond_name')
                    ->where('kapan_parts_id', $request->kapan_parts_id),
            ],
            'kapans_id' => 'required',
            'kapan_parts_id' => 'required',
            // 'weight' => 'required|numeric|min:0',
            // 'prediction_weight' => 'nullable|numeric|min:0|lte:weight',
            'prediction_weight' => 'nullable|numeric|min:0',
            'shape' => 'required',
        ]);

        // 🔹 Existing weight
        $existingWeight = Diamond::where('kapan_parts_id', $request->kapan_parts_id)->sum('prediction_weight');

        if (($existingWeight + $request->prediction_weight) > $kapanPart->weight) {
            return back()
                ->withInput()
                ->with('error', 'Total diamond weight cannot exceed Kapan Part weight (' . $kapanPart->weight . ')');
        }

        // 🔹 Barcode generation
        do {
            $number = mt_rand(1000000000, 9999999999);
        } while (Diamond::where('barcode_number', $number)->exists());

        Diamond::create([
            'kapans_id' => $request->kapans_id,
            'kapan_parts_id' => $request->kapan_parts_id,
            'diamond_name' => $finalDiamondName,
            // 'weight' => $request->weight,
            'prediction_weight' => $request->prediction_weight ?? 0,
            'shape' => $request->shape,
            'barcode_number' => $number,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.sub-division.index', [
            'kapans_id' => $request->kapans_id,
            'kapan_parts_id' => $request->kapan_parts_id,
        ])->with('success', 'Diamond added successfully');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'diamond_name' => 'required|unique:diamonds,diamond_name,NULL,id,kapan_parts_id,' . $request->kapan_parts_id,
    //         'kapans_id' => 'required',
    //         'kapan_parts_id' => 'required',
    //         'weight' => 'required|numeric|min:0',
    //         'prediction_weight' => 'nullable|numeric|min:0|lte:weight',
    //         'shape' => 'required',
    //     ]);

    //     // 🔹 Rule 1: prediction_weight <= weight
    //     if ($request->prediction_weight > $request->weight) {
    //         return back()
    //             ->withInput()
    //             ->with('error', 'Prediction weight cannot be greater than weight');
    //     }

    //     // 🔹 Get Kapan Part total allowed weight
    //     $kapanPart = DB::table('kapan_parts')->where('id', $request->kapan_parts_id)->first();

    //     if (!$kapanPart) {
    //         return back()->with('error', 'Invalid Kapan Part');
    //     }

    //     // 🔹 Sum of existing diamonds weight
    //     $existingWeight = Diamond::where('kapan_parts_id', $request->kapan_parts_id)
    //         ->sum('weight');

    //     // 🔹 Rule 2: total diamonds weight <= kapan part weight
    //     if (($existingWeight + $request->weight) > $kapanPart->weight) {
    //         return back()
    //             ->withInput()
    //             ->with(
    //                 'error',
    //                 'Total diamond weight cannot exceed Kapan Part weight (' . $kapanPart->weight . ')'
    //             );
    //     }

    //     $number = mt_rand(1000000000, 9999999999);

    //     while ($this->dimondCodeExists($number)) {
    //         $number = mt_rand(1000000000, 9999999999);
    //     }

    //     Diamond::create([
    //         'kapans_id' => $request->kapans_id,
    //         'kapan_parts_id' => $request->kapan_parts_id,
    //         'diamond_name' => $kapanPart->name . '-' . $request->diamond_name,
    //         'weight' => $request->weight ?? 0,
    //         'prediction_weight' => $request->prediction_weight ?? 0,
    //         'shape' => $request->shape,
    //         'barcode_number' => $number,
    //         'status' => 'pending',
    //     ]);

    //     return redirect()->route('admin.sub-division.index', [
    //         'kapans_id' => $request->kapans_id,
    //         'kapan_parts_id' => $request->kapan_parts_id,
    //     ])->with('success', 'Diamond added successfully');
    // }

    public function dimondCodeExists($number)
    {
        return Diamond::where('barcode_number', $number)->exists();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'diamond_name' => 'required',
            // 'weight' => 'required|numeric|min:0',
            'prediction_weight' => 'nullable|numeric|min:0',
            'shape' => 'required',
        ]);

        // 🔹 Rule 1: prediction_weight <= weight
        // if ($request->prediction_weight > $request->weight) {
        //     return back()
        //         ->withInput()
        //         ->with('error', 'Prediction weight cannot be greater than weight');
        // }

        $diamond = Diamond::findOrFail($id);

        // 🔹 Get Kapan Part weight
        $kapanPart = DB::table('kapan_parts')
            ->where('id', $request->kapan_parts_id)
            ->first();

        // 🔹 Sum weights EXCEPT current diamond
        $existingWeight = Diamond::where('kapan_parts_id', $request->kapan_parts_id)
            ->where('id', '!=', $id)
            ->sum('prediction_weight');

        if (($existingWeight + $request->prediction_weight) > $kapanPart->weight) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Total diamond weight cannot exceed Kapan Part weight (' . $kapanPart->weight . ')'
                );
        }

        $diamond->update([
            'diamond_name' => $request->diamond_name,
            // 'weight' => $request->weight,
            'prediction_weight' => $request->prediction_weight,
            'shape' => $request->shape,
        ]);

        return redirect()->route('admin.sub-division.index', [
            'kapans_id' => $request->kapans_id,
            'kapan_parts_id' => $request->kapan_parts_id,
        ])->with('success', 'Diamond updated successfully');
    }

    public function delete(Request $request, $id)
    {
        Diamond::findOrFail($id)->delete();

        return redirect()->route('admin.sub-division.index', [
            'kapans_id' => $request->kapans_id,
            'kapan_parts_id' => $request->kapan_parts_id,
        ])->with('success', 'Diamond deleted successfully');
    }

    public function index(Request $request)
    {
        $kapans = Kapan::where('is_active', 1)->orderBy('id', 'DESC')->get();
        $diamonds = Diamond::with(['kapan', 'kapanPart'])
            ->where('status', 'pending')
            ->orderBy('id', 'DESC')
            ->get();

        if ($request->kapans_id) {
            $diamonds = Diamond::with(['kapan', 'kapanPart'])
                ->where('status', 'pending')
                ->when($request->kapans_id, function ($q) use ($request) {
                    $q->where('kapans_id', $request->kapans_id);
                })
                ->orderBy('id', 'DESC')
                ->get();
        }

        return view('admin.diamond.index', compact('kapans', 'diamonds'));
    }

    public function edit($id)
    {
        $diamond = Diamond::with([
            'issues.designation',
            'issues.worker'
        ])->findOrFail($id);

        return view('admin.diamond.edit', compact('diamond'));
    }

    public function updateByEdit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'diamond_name' => 'required',
            // 'janger_no' => 'required',
            'weight' => 'required',
            'prediction_weight' => 'required',
            // 'shape' => 'required',
            // 'clarity' => 'required',
            // 'color' => 'required',
            // 'cut' => 'required',
            // 'polish' => 'required',
            // 'symmetry' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $diamond = Diamond::findOrFail($id);
        $input = $request->all();
        $diamond->update($input);
        // return redirect('admin/diamonds')->with('success', "Update Record Successfully");
        return redirect()->back()->with('success', "Update Record Successfully");
    }

    public function destroy($id)
    {
        $diamond = Diamond::findOrFail($id);
        $diamond->delete();
        return Redirect::back()->with('success', "Delete Record Successfully");
    }

    public function purchase()
    {
        $purchases = Purchase::where('is_sell', 0)->get();
        $partys = Party::where('type', 'party')->where('is_active', 1)->get();
        $brokers = Party::where('type', 'broker')->where('is_active', 1)->get();
        return view('admin.diamond.purchase', compact('purchases', 'partys', 'brokers'));
    }

    public function purchaseEdit($id)
    {
        $designations = Designation::get();
        $shapes = Shape::get();
        $colors = Color::get();
        $clarity = Clarity::get();
        $polish = Polish::get();
        $symmetry = Symmetry::get();

        $Purchase = Purchase::findOrFail($id);
        if (!$Purchase) {
            return Redirect::back()->with('error', "Invalid id");
        }

        $data = Issue::where('designation_id', 3)->where('diamonds_id', $Purchase->diamonds_id)->where('is_return', 1)->first();

        $partys = Party::where('is_active', 1)->get();

        return view('admin.diamond.purchase_edit', compact(
            'designations',
            'partys',
            'data',
            'shapes',
            'colors',
            'clarity',
            'symmetry',
            'polish'
        ));
    }

    public function purchaseUpdate(Request $request, $id)
    {
        $issue = Issue::findOrFail($id);
        $input = $request->all();

        $diamond = Diamond::where('id', $issue->diamonds_id)->first();

        if ($issue->designation_id == 3 && !empty($request->certi_no)) {
            $diamond->update(['status' => 'purchased']);
        }

        $issue->update($input);

        return redirect('admin/purchase')->with('success', 'updated successfully');
    }

    public function sellStore(Request $request)
    {
        $request->validate([
            'purchase_id'     => 'required',
            'diamonds_id'     => 'required',
            'rate_per_ct'     => 'required|numeric',
            'total_amount'    => 'required|numeric',
            'less_brokerage'  => 'nullable|numeric',
            'final_amount'    => 'required|numeric',
            'parties_id'      => 'nullable|numeric',
            'payment_type'   => 'required|string',
            'payment_status'  => 'required|in:paid,unpaid',
            'sell_date'       => 'required|date',
            'dollar_rate'     => 'required|numeric',
            // 'note'       => 'required|string',
        ]);

        $alreadySold = Sell::where('purchase_id', $request->purchase_id)
            ->where('diamonds_id', $request->diamonds_id)
            ->exists();

        if ($alreadySold) {
            return redirect()->back()
                ->with('error', 'This diamond is already sold for this purchase.');
        }

        Sell::create($request->all());

        Purchase::where('id', $request->purchase_id)->update(['is_sell' => 1]);

        return redirect()->back()->with('success', 'Diamond sell entry saved successfully');
    }

    public function sellList()
    {
        $sells = Sell::get();
        return view('admin.sell.index', compact('sells'));
    }

    public function sellEdit($id)
    {
        $sell = Sell::findOrFail($id);
        $diamond = Diamond::where('id', $sell->diamonds_id)->first();
        $partys = Party::where('type', 'party')->where('is_active', 1)->get();
        $brokers = Party::where('type', 'broker')->where('is_active', 1)->get();
        return view('admin.sell.edit', compact('sell', 'partys', 'brokers', 'diamond'));
    }

    public function sellUpdate(Request $request, $id)
    {
        $sell = Sell::findOrFail($id);
        $input = $request->all();

        // Validation
        $validator = Validator::make($request->all(), [
            'purchase_id'     => 'required',
            'diamonds_id'     => 'required',
            'rate_per_ct'     => 'required|numeric',
            'total_amount'    => 'required|numeric',
            'less_brokerage'  => 'nullable|numeric',
            'final_amount'    => 'required|numeric',
            'parties_id'      => 'nullable|numeric',
            'payment_type'   => 'required|string',
            'payment_status'  => 'required|in:paid,unpaid',
            'sell_date'       => 'required|date',
            'dollar_rate'     => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($input)->withErrors($validator);
        }

        // Update Party Information
        $sell->update($input);

        return redirect('admin/sell')->with('success', 'updated successfully');
    }


    public function export()
    {
        return Excel::download(
            new PurchaseDiamondsExport,
            'purchased_diamonds_' . now()->format('d-m-Y') . '.xlsx'
        );
    }
}
