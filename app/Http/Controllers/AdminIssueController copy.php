<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Issue;
use App\Models\Kapan;
use App\Models\Diamond;
use App\Models\Designation;
use App\Models\KapanPart;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AdminIssueController extends Controller
{
    public function index(Request $request)
    {
        $designations = Designation::get();
        $kapans = Kapan::where('is_active', 1)->orderBy('id', 'DESC')->get();

        $workers = [];
        $kapan_parts = [];
        $diamonds = collect();

        if ($request->filled('kapans_id')) {

            $diamonds = Diamond::with('shapes')
                ->where('kapans_id', $request->kapans_id)

                ->when($request->filled('kapan_parts_id'), function ($q) use ($request) {
                    $q->where('kapan_parts_id', $request->kapan_parts_id);
                })

                // remove currently issued diamonds
                ->whereNotIn('id', function ($q) {
                    $q->select('diamonds_id')
                        ->from('issues')
                        ->where('is_return', 0);
                })

                ->orderBy('id', 'ASC')
                ->get();

            $kapan_parts = KapanPart::where('kapans_id', $request->kapans_id)->get();
        }

        if ($request->filled('designation')) {
            $workers = Worker::where('designation', $request->designation)->where('is_active', 1)->get();
        }

        return view('admin.issue.index', compact(
            'designations',
            'kapans',
            'workers',
            'kapan_parts',
            'diamonds'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'designation_id' => 'required',
            'worker_id'      => 'required',
            'kapans_id'      => 'required',
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

            $diamond->status = "processing";
            $diamond->save();
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
        $kapans = Kapan::where('is_active', 1)->orderBy('id', 'DESC')->get();

        $workers = [];
        $kapan_parts = [];
        $issues = collect();

        if ($request->filled('designation')) {
            $workers = Worker::where('designation', $request->designation)
                ->where('is_active', 1)
                ->get();
        }

        if ($request->filled('kapans_id') && $request->filled('worker_id')) {

            $issues = Issue::with('diamond')
                ->where('kapans_id', $request->kapans_id)
                ->where('worker_id', $request->worker_id)
                ->where('is_return', 0)

                ->when($request->filled('kapan_parts_id'), function ($q) use ($request) {
                    $q->where('kapan_parts_id', $request->kapan_parts_id);
                })

                ->orderBy('id', 'ASC')
                ->get();

            $kapan_parts = KapanPart::where('kapans_id', $request->kapans_id)->get();
        }

        return view('admin.issue.return', compact(
            'designations',
            'kapans',
            'workers',
            'kapan_parts',
            'issues'
        ));
    }

    public function storeReturn(Request $request)
    {
        $request->validate([
            'issue_id'      => 'required|exists:issues,id',
            'return_weight' => 'required|numeric|min:0',
            'return_date'   => 'required|date',
        ]);

        $issue = Issue::findOrFail($request->issue_id);

        if ($request->return_weight > $issue->issue_weight) {
            return back()->with('error', 'Return weight cannot be greater than Issue weight');
        }

        $issue->update([
            'return_weight' => $request->return_weight,
            'return_date'   => $request->return_date,
            'is_return'     => 1,
            'r_color'       => $request->r_color ?? NULL,
            'r_shape'       => $request->r_shape ?? NULL,
            'r_cut'         => $request->r_cut ?? NULL,
        ]);

        return back()->with('success', 'Diamond returned successfully');
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
