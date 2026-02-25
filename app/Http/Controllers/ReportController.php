<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Issue;
use App\Models\Kapan;
use App\Models\Khata;
use App\Models\Worker;
use App\Models\Diamond;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function expenseSummary(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;
        $khata_id = $request->khata_id;

        $query = Khata::where('type', 'expense');

        if ($khata_id) {
            $query->where('id', $khata_id);
        }

        $khatas = $query->withSum(['khatabills as total_bill' => function ($q) use ($from, $to) {
            if ($from) $q->whereDate('bill_date', '>=', $from);
            if ($to)   $q->whereDate('bill_date', '<=', $to);
        }], 'amount')

            ->withSum(['incomes as total_received' => function ($q) use ($from, $to) {
                if ($from) $q->whereDate('income_date', '>=', $from);
                if ($to)   $q->whereDate('income_date', '<=', $to);
            }], 'amount')

            ->withSum(['expenses as total_paid' => function ($q) use ($from, $to) {
                if ($from) $q->whereDate('expense_date', '>=', $from);
                if ($to)   $q->whereDate('expense_date', '<=', $to);
            }], 'amount')

            ->get();

        foreach ($khatas as $k) {

            $bill     = $k->total_bill ?? 0;
            $received = $k->total_received ?? 0;
            $paid     = $k->total_paid ?? 0;

            $k->balance = ($bill - $received) - $paid;
        }

        // Grand Totals
        $total_bill     = $khatas->sum('total_bill');
        $total_received = $khatas->sum('total_received');
        $total_paid     = $khatas->sum('total_paid');
        $company_balance = ($total_bill - $total_received) - $total_paid;

        $allKhatas = Khata::where('type', 'expense')
            ->select('id', 'fname', 'lname')
            ->get();

        return view('admin.reports.expense_summary', compact(
            'khatas',
            'total_bill',
            'total_received',
            'total_paid',
            'company_balance',
            'allKhatas'
        ));
    }

    public function incomeSummary(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;
        $khata_id = $request->khata_id;

        $query = Khata::where('type', 'income');

        if ($khata_id) {
            $query->where('id', $khata_id);
        }

        $khatas = $query
            ->withSum(['khatabills as total_bill' => function ($q) use ($from, $to) {
                if ($from) $q->whereDate('bill_date', '>=', $from);
                if ($to)   $q->whereDate('bill_date', '<=', $to);
            }], 'amount')

            ->withSum(['incomes as total_received' => function ($q) use ($from, $to) {
                if ($from) $q->whereDate('income_date', '>=', $from);
                if ($to)   $q->whereDate('income_date', '<=', $to);
            }], 'amount')

            ->get();

        foreach ($khatas as $k) {
            $bill     = $k->total_bill ?? 0;
            $received = $k->total_received ?? 0;

            $k->balance = $received - $bill;   // 👈 income logic
        }

        $total_bill     = $khatas->sum('total_bill');
        $total_received = $khatas->sum('total_received');
        $company_balance = $total_received - $total_bill;

        $allKhatas = Khata::where('type', 'income')
            ->select('id', 'fname', 'lname')
            ->get();

        return view('admin.reports.income_summary', compact(
            'khatas',
            'total_bill',
            'total_received',
            'company_balance',
            'allKhatas'
        ));
    }

    public function ledger(Request $request, $id)
    {
        $from = $request->from;
        $to   = $request->to;

        $khata = Khata::findOrFail($id);

        $entries = collect();

        /*
    |--------------------------------------------------------------------------
    | BILL (Always Debit)
    |--------------------------------------------------------------------------
    */
        $bills = $khata->khatabills()
            ->when($from, fn($q) => $q->whereDate('bill_date', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('bill_date', '<=', $to))
            ->get();

        foreach ($bills as $b) {
            $entries->push([
                'date'   => $b->bill_date,
                'type'   => 'Bill',
                'debit'  => $b->amount,
                'credit' => 0,
                'note'   => $b->note
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | RECEIVED (Always Credit)
    |--------------------------------------------------------------------------
    */
        $incomes = $khata->incomes()
            ->when($from, fn($q) => $q->whereDate('income_date', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('income_date', '<=', $to))
            ->get();

        foreach ($incomes as $i) {
            $entries->push([
                'date'   => $i->income_date,
                'type'   => 'Received',
                'debit'  => 0,
                'credit' => $i->amount,
                'note'   => $i->note
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | PAID (Expense Account Only → Credit)
    |--------------------------------------------------------------------------
    */
        if ($khata->type == 'expense') {

            $expenses = $khata->expenses()
                ->when($from, fn($q) => $q->whereDate('expense_date', '>=', $from))
                ->when($to, fn($q) => $q->whereDate('expense_date', '<=', $to))
                ->get();

            foreach ($expenses as $e) {
                $entries->push([
                    'date'   => $e->expense_date,
                    'type'   => 'Paid',
                    'debit'  => 0,              // ✔ FIXED
                    'credit' => $e->amount,     // ✔ Correct
                    'note'   => $e->note
                ]);
            }
        }

        /*
    |--------------------------------------------------------------------------
    | SORT BY DATE
    |--------------------------------------------------------------------------
    */
        $entries = $entries->sortBy('date')->values();

        /*
    |--------------------------------------------------------------------------
    | BALANCE CALCULATION
    |--------------------------------------------------------------------------
    */
        $balance = 0;

        $entries = $entries->map(function ($row) use (&$balance) {
            $balance += $row['debit'];
            $balance -= $row['credit'];
            $row['balance'] = $balance;
            return $row;
        });

        return view('admin.reports.ledger', compact('khata', 'entries'));
    }

    public function index(Request $request)
    {
        $query = Issue::with(['worker.designations', 'diamond', 'kapans']);

        // Designation Filter
        if ($request->filled('designation')) {
            $query->whereHas('worker', function ($q) use ($request) {
                $q->where('designation', $request->designation);
            });
        }

        // Worker Filter
        if ($request->filled('worker_id')) {
            $query->where('worker_id', $request->worker_id);
        }

        // Kapan Filter
        if ($request->filled('kapans_id')) {
            $query->where('kapans_id', $request->kapans_id);
        }

        // Status Filter
        if ($request->status == 'returned') {
            $query->whereNotNull('return_date')
                ->where('return_weight', '>', 0);
        }

        if ($request->status == 'pending') {
            $query->where(function ($q) {
                $q->whereNull('return_date')
                    ->orWhere('return_weight', 0);
            });
        }

        // Date Range
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('issue_date', [
                $request->from_date,
                $request->to_date
            ]);
        }

        $reports = $query->latest()->get();

        $totalIssueWeight = $reports->sum('issue_weight');
        $totalReturnWeight = $reports->sum('return_weight');
        $totalPendingWeight = $totalIssueWeight - $totalReturnWeight;

        $designations = Designation::all();
        $kapans = Kapan::all();

        return view('admin.reports.worker_report', compact(
            'reports',
            'designations',
            'kapans',
            'totalIssueWeight',
            'totalReturnWeight',
            'totalPendingWeight'
        ));
    }

    public function getWorkers(Request $request)
    {
        $workers = Worker::where('designation', $request->designation)
            ->where('is_active', 1)
            ->get();

        return response()->json($workers);
    }

    public function kapanReport(Request $request)
    {
        $query = Kapan::select('kapans.id', 'kapans.kapan_name', 'kapans.kapan_weight');

        // Kapan Name Filter
        if ($request->kapan_name) {
            $query->where('kapan_name', 'like', '%' . $request->kapan_name . '%');
        }

        $kapans = $query
            ->withCount(['parts as kapan_parts'])
            ->withCount(['diamonds as total_diamonds'])
            ->withSum('diamonds as prediction_weight', 'prediction_weight')
            ->withSum(['issues as return_weight' => function ($q) use ($request) {

                $q->where('designation_id', 3)
                    ->where('is_return', 1)
                    ->whereNotNull('return_date')
                    ->whereNotNull('return_weight');

                // Date Filter
                if ($request->from_date && $request->to_date) {
                    $q->whereBetween('issue_date', [
                        $request->from_date,
                        $request->to_date
                    ]);
                }
            }], 'return_weight')
            ->withCount(['issues as total_pending_process_diamond' => function ($q) use ($request) {

                $q->where(function ($query) {
                    $query->where('is_return', 0)
                        ->orWhereNull('return_date')
                        ->orWhere('return_weight', 0);
                });

                if ($request->from_date && $request->to_date) {
                    $q->whereBetween('issue_date', [
                        $request->from_date,
                        $request->to_date
                    ]);
                }
            }])
            ->get()
            ->map(function ($kapan) {

                $kapanWeight = $kapan->kapan_weight ?? 0;
                $predictionWeight = $kapan->prediction_weight ?? 0;
                $returnWeight = $kapan->return_weight ?? 0;

                // Avoid divide by zero
                $kapan->prediction_percent = $kapanWeight > 0
                    ? ($predictionWeight * 100) / $kapanWeight
                    : 0;

                $kapan->return_percent = $kapanWeight > 0
                    ? ($returnWeight * 100) / $kapanWeight
                    : 0;

                return $kapan;
            });

        return view('admin.reports.kapan_report', compact('kapans'));
    }

    public function kapanDetail($id)
    {
        $kapan = Kapan::withCount('parts')
            ->withCount('diamonds')
            ->withSum('diamonds as prediction_weight', 'prediction_weight')
            ->withSum(['issues as return_weight' => function ($q) {
                $q->where('designation_id', 3)
                    ->where('is_return', 1)
                    ->whereNotNull('return_date')
                    ->whereNotNull('return_weight');
            }], 'return_weight')
            ->findOrFail($id);

        $diamonds = Diamond::where('kapans_id', $id)
            ->with('issues')
            ->orderByRaw("
            CASE
                WHEN status = 'pending' THEN 1
                WHEN status = 'purchased' THEN 2
                WHEN status = 'sell' THEN 3
                ELSE 4
            END
        ")
            ->orderBy('diamond_name') // optional secondary sorting
            ->get();

        return view(
            'admin.reports.kapan_detail',
            compact('kapan', 'diamonds')
        );
    }

    public function sellReport(Request $request)
    {
        $kapans = Kapan::query()

            ->withCount('parts')
            ->withCount('diamonds')

            ->withCount([
                'diamonds as sold_diamonds_count' => function ($q) {
                    $q->whereHas('sell');
                }
            ])

            ->withCount([
                'diamonds as purchase_diamonds_count' => function ($q) {
                    $q->whereHas('purchase', function ($sub) {
                        $sub->where('is_sell', 0);
                    });
                }
            ])

            ->withSum('diamonds as total_diamond_weight', 'prediction_weight')

            ->addSelect([
                'total_sell_amount' => DB::table('diamonds')
                    ->join('sells', 'sells.diamonds_id', '=', 'diamonds.id')
                    ->whereColumn('diamonds.kapans_id', 'kapans.id')
                    ->selectRaw('COALESCE(SUM(sells.final_amount),0)')
            ])

            ->addSelect([
                'sold_weight' => DB::table('diamonds')
                    ->join('issues', 'issues.diamonds_id', '=', 'diamonds.id')
                    ->join('sells', 'sells.diamonds_id', '=', 'diamonds.id')
                    ->whereColumn('diamonds.kapans_id', 'kapans.id')
                    ->where('issues.designation_id', 3)
                    ->where('issues.is_return', 1)
                    ->whereNotNull('issues.return_weight')
                    ->whereNotNull('issues.return_date')
                    ->selectRaw('COALESCE(SUM(issues.return_weight),0)')
            ])

            ->withCount([
                'diamonds as pending_diamonds_count' => function ($q) {
                    $q->where(function ($query) {
                        $query->whereDoesntHave('issues')
                            ->orWhereHas('issues', function ($sub) {
                                $sub->where('is_return', 0);
                            });
                    });
                }
            ])
            ->get();

        return view('admin.reports.sell_report', compact('kapans'));
    }

    public function sellDetail($id)
    {
        $kapan = Kapan::findOrFail($id);

        $diamonds = Diamond::where('diamonds.kapans_id', $id)
            ->join('sells', 'sells.diamonds_id', '=', 'diamonds.id')
            ->with([
                'sell',
                'issues' => function ($q) {
                    $q->where('designation_id', 3)
                        ->where('is_return', 1)
                        ->whereNotNull('return_date');
                }
            ])
            // 🔥 Unpaid First, Paid After
            ->orderByRaw("FIELD(sells.payment_status, 'unpaid', 'paid')")
            ->select('diamonds.*')
            ->get();

        // 🔹 Summary Calculations
        $totalPrediction = $diamonds->sum('prediction_weight');

        $totalSoldWeight = $diamonds->sum(function ($diamond) {
            return $diamond->issues->sum('return_weight');
        });

        $totalSellAmount = $diamonds->sum(function ($diamond) {
            return optional($diamond->sell)->final_amount;
        });

        $returnPercent = $totalPrediction > 0
            ? ($totalSoldWeight / $totalPrediction) * 100
            : 0;

        // Paid Amount
        $paidAmount = $diamonds->sum(function ($diamond) {
            return optional($diamond->sell)->payment_status === 'paid'
                ? optional($diamond->sell)->final_amount
                : 0;
        });

        // Unpaid Amount
        $unpaidAmount = $diamonds->sum(function ($diamond) {
            return optional($diamond->sell)->payment_status === 'unpaid'
                ? optional($diamond->sell)->final_amount
                : 0;
        });

        // Unpaid Diamonds Count (optional useful)
        $unpaidDiamondsCount = $diamonds->filter(function ($diamond) {
            return optional($diamond->sell)->payment_status === 'unpaid';
        })->count();

        return view('admin.reports.sell_detail', compact(
            'kapan',
            'diamonds',
            'totalPrediction',
            'totalSoldWeight',
            'totalSellAmount',
            'returnPercent',
            'paidAmount',
            'unpaidAmount',
            'unpaidDiamondsCount'
        ));
    }
}
