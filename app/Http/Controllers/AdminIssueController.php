<?php

namespace App\Http\Controllers;

use App\Models\Clarity;
use App\Models\Color;
use Validator;
use App\Models\Issue;
use App\Models\Kapan;
use App\Models\Diamond;
use App\Models\Designation;
use App\Models\KapanPart;
use App\Models\Polish;
use App\Models\Purchase;
use App\Models\Shape;
use App\Models\Symmetry;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AdminIssueController extends Controller
{
    public function index(Request $request)
    {
        $designations = Designation::get();
        $kapans = Kapan::where('is_active', 1)->get();

        $kapan_parts = [];
        $workers = collect();

        $diamondsQuery = Diamond::with(['shapes', 'issues.designation'])

            // ❌ Diamond currently issued (not returned)
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('issues')
                    ->whereColumn('issues.diamonds_id', 'diamonds.id')
                    ->where('issues.is_return', 0);
            })

            // ❌ Already issued to designation_id = 3 (your existing logic)
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('issues')
                    ->whereColumn('issues.diamonds_id', 'diamonds.id')
                    ->where('issues.designation_id', 3);
            })

            // ❌ LAST issue return_weight = 0 → hide diamond
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('issues as i2')
                    ->whereColumn('i2.diamonds_id', 'diamonds.id')
                    ->where('i2.return_weight', 0)->where('i2.is_return', 1)
                    ->whereRaw('i2.id = (
                    SELECT MAX(i3.id)
                    FROM issues i3
                    WHERE i3.diamonds_id = diamonds.id
                )');
            });

        if ($request->filled('kapans_id')) {
            $diamondsQuery->where('kapans_id', $request->kapans_id);

            if ($request->filled('kapan_parts_id')) {
                $diamondsQuery->where('kapan_parts_id', $request->kapan_parts_id);
            }

            $kapan_parts = KapanPart::where('kapans_id', $request->kapans_id)->get();
        }

        $diamonds = $diamondsQuery->get();

        return view('admin.issue.index', compact(
            'designations',
            'kapans',
            'kapan_parts',
            'diamonds',
            'workers'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'designation_id' => 'required',
            'worker_id'      => 'required',
            // 'kapans_id'      => 'required',
            'diamonds_id'    => 'required|array|min:1',
        ], [
            'diamonds_id.required' => 'Please select at least one Diamond',
            'diamonds_id.min'      => 'Please select at least one Diamond',
        ]);

        foreach ($request->diamonds_id as $diamondId) {

            $diamond = DB::table('diamonds')->where('id', $diamondId)->first();

            if (!$diamond) {
                continue;
            }

            $alreadyIssued = Issue::where('diamonds_id', $diamondId)
                ->where('is_return', 0)
                ->exists();

            if ($alreadyIssued) {
                continue;
            }

            $lastReturnedIssue = Issue::where('diamonds_id', $diamondId)
                ->where('is_return', 1)
                ->orderBy('id', 'DESC')
                ->first();


            $issueWeight = $lastReturnedIssue
                ? $lastReturnedIssue->return_weight
                : $diamond->weight;

            Issue::create([
                'designation_id' => $request->designation_id,
                'worker_id'      => $request->worker_id,
                'kapans_id'       => $diamond->kapans_id,
                'kapan_parts_id'  => $diamond->kapan_parts_id,
                'diamonds_id'     => $diamondId,
                'issue_weight'    => $issueWeight,
                'issue_date'      => now()->format('Y-m-d'),
                'is_return'       => 0
            ]);

            // $diamond->status = "processing";
            // $diamond->save();
        }

        return redirect()
            ->route('admin.issue.index', [
                'designation' => $request->designation_id,
                'worker_id'   => $request->worker_id,
                'kapans_id'   => $request->kapans_id,
            ])
            ->with('success', 'Diamonds issued successfully');
    }

    public function return(Request $request)
    {
        $designations = Designation::get();
        $shapes = Shape::get();
        $colors = Color::get();
        $clarity = Clarity::get();
        $polish = Polish::get();
        $symmetry = Symmetry::get();
        $kapans = Kapan::where('is_active', 1)->orderBy('id', 'DESC')->get();

        $workers = [];
        $kapan_parts = [];
        // $issues = Issue::with('diamond')
        //     ->where('is_return', 0)
        //     ->orderBy('id', 'ASC')
        //     ->get();

        $issues = [];

        if ($request->filled('designation')) {
            $workers = Worker::where('designation', $request->designation)
                ->where('is_active', 1)
                ->get();
        }

        // if ($request->filled('kapans_id') && $request->filled('worker_id')) {
        if ($request->filled('worker_id')) {

            $issues = Issue::with('diamond')
                ->where('worker_id', $request->worker_id)
                ->where('is_return', 0)

                ->when($request->filled('kapans_id'), function ($q) use ($request) {
                    $q->where('kapans_id', $request->kapans_id);
                })

                ->when($request->filled('kapan_parts_id'), function ($q) use ($request) {
                    $q->where('kapan_parts_id', $request->kapan_parts_id);
                })

                ->orderBy('id', 'ASC')
                ->get();

            if ($request->filled('kapans_id')) {
                $kapan_parts = KapanPart::where('kapans_id', $request->kapans_id)->get();
            }
        }

        return view('admin.issue.return', compact(
            'designations',
            'kapans',
            'workers',
            'kapan_parts',
            'issues',
            'shapes',
            'colors',
            'clarity',
            'symmetry',
            'polish'
        ));
    }

    public function storeReturn(Request $request)
    {
        $request->validate([
            'issue_id'      => 'required|exists:issues,id',
            'return_weight' => 'required|numeric|min:0',
            'return_date'   => 'required|date',
            // 'certi_no'      => 'required',
        ]);

        $issue = Issue::findOrFail($request->issue_id);

        $diamond = Diamond::where('id', $issue->diamonds_id)->first();

        if ($request->return_weight > $diamond->prediction_weight) {
            return back()->with('error', 'Return weight cannot be greater than diamond weight');
        }

        $data = $request->except(['issue_id', '_token']);
        $data['is_return'] = 1;
        $data['total_price'] = $request->return_weight * $request->price;

        $issue->update($data);

        // $issue->update([
        //     'return_weight' => $request->return_weight,
        //     'return_date'   => $request->return_date,
        //     'is_return'     => 1,
        //     'r_color'       => $request->r_color ?? NULL,
        //     'r_shape'       => $request->r_shape ?? NULL,
        //     'r_clarity'     => $request->r_clarity ?? NULL,
        //     'certi_no'     => $request->certi_no ?? NULL,
        // ]);

        if ($issue->designation_id == 3 && !empty($request->certi_no)) {
            $diamond->update(['status' => 'purchased']);

            Purchase::firstOrCreate([
                'diamonds_id' => $issue->diamonds_id
            ]);
        }

        return back()->with('success', 'Diamond returned successfully');
    }

    public function updateInline(Request $request, $id)
    {
        $request->validate([
            // 'issue_weight'  => 'required|numeric|min:0',
            // 'return_weight' => 'nullable|numeric|min:0|lte:issue_weight',
            'return_weight' => 'nullable|numeric|min:0',
            'issue_date'    => 'required|date',
            'return_date'   => 'nullable|date|after_or_equal:issue_date',
        ], [
            'return_weight.lte' => 'Return weight cannot be greater than issue weight',
        ]);

        $issue = Issue::findOrFail($id);

        $issue->update([
            // 'issue_weight'  => $request->issue_weight,
            'issue_date'    => $request->issue_date,
            'return_weight' => $request->return_weight,
            'return_date'   => $request->return_date,
            'is_return'     => $request->return_date ? 1 : 0,
        ]);

        // $issueCount = Issue::where('diamonds_id', $issue->diamonds_id)->count();

        // Diamond::where('id', $issue->diamonds_id)->update([
        //     'status' => $issueCount == 0 ? 'pending' : 'processing'
        // ]);

        return response()->json([
            'status' => true
        ]);
    }

    public function deleteInline($id)
    {
        $issue = Issue::findOrFail($id);
        // $diamondId = $issue->diamonds_id;

        $issue->delete();

        // 🔁 Check remaining issues
        // $remainingIssues = Issue::where('diamonds_id', $diamondId)->count();

        // Diamond::where('id', $diamondId)->update([
        //     'status' => $remainingIssues == 0 ? 'pending' : 'processing'
        // ]);

        return response()->json(['status' => true]);
    }

    public function getIssuedKapanParts(Request $request)
    {
        $kapanId = $request->input('kapans_id');
        $issues = Issue::with('kapanPart')
            ->where('kapans_id', $kapanId)
            ->get();

        return response()->json($issues);
    }
}
