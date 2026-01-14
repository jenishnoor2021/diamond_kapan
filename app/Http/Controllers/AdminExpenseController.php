<?php

namespace App\Http\Controllers;

use App\Models\Khata;
use App\Models\Income;
use App\Models\Expense;
use App\Models\KhataBill;
use Illuminate\Http\Request;

class AdminExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $khatas = Khata::where('type', 'expense')
            ->withSum('khatabills as total_bill_amount', 'amount')
            ->withSum('expenses as total_expense_amount', 'amount')
            ->get();

        $totalIncomeAmount  = Income::sum('amount');
        $totalExpenseAmount = Expense::sum('amount');

        return view('admin.expense.index', compact(
            'khatas',
            'totalIncomeAmount',
            'totalExpenseAmount'
        ));
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editExpense($id)
    {
        $khata = Khata::findOrFail($id);

        $khatabills = KhataBill::where('khatas_id', $khata->id)->get();
        $expenses   = Expense::where('khatas_id', $khata->id)->get();

        $totalBillAmount    = $khatabills->sum('amount');
        $totalExpenseAmount = $expenses->sum('amount');
        $remainingAmount    = $totalBillAmount - $totalExpenseAmount;

        return view('admin.expense.edit', compact(
            'khata',
            'khatabills',
            'expenses',
            'totalBillAmount',
            'totalExpenseAmount',
            'remainingAmount'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'khatas_id' => 'required',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric',
            'payment_type' => 'required'
        ]);

        Expense::create($request->all());

        return redirect()->back()->with('success', 'Expense added successfully');
    }

    public function edit($id)
    {
        return Expense::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'amount' => 'required|numeric',
            'payment_type' => 'required'
        ]);

        Expense::findOrFail($id)->update($request->all());

        return redirect()->back()->with('success', 'Expense updated successfully');
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
