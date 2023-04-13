<?php

namespace App\Http\Controllers;

use App\Enums\ExpenseCategory;
use App\Enums\ExpensePaymentType;
use App\Enums\ExpenseType;
use App\Http\Requests\Expenses\StoreExpenseRequest;
use App\Models\Expense;
use App\Models\ExpensePayment;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    public function list()
    {
        return Inertia::render('Expense/List');
    }

    public function store(StoreExpenseRequest $request)
    {
        $expense = new Expense();
        $expense->user_id = auth()->id();
        $expense->type = ExpenseType::from($request->string('type'));
        $expense->name = $request->string('name');
        $expense->value = $request->float('value');
        $expense->start_date = $request->date('start_date');
        $expense->end_date = $request->date('end_date');
        $expense->category = ExpenseCategory::from($request->string('category'));
        $expense->payment_type = ExpensePaymentType::from($request->string('payment_type'));

        if ($expense->type == ExpenseType::ONE_TIME) {
            $expense->months = [];
        } elseif ($expense->type == ExpenseType::MONTHLY) {
            $expense->months = [];
        } elseif ($expense->type == ExpenseType::SELECTED_MONTHS) {
            $expense->day = $request->integer('day');
            $expense->month = now()->month;
            $expense->months = $request->input('months');
        } else {
            $expense->day = $request->integer('day');
            $expense->month = $request->integer('month');
            $expense->months = [];
        }
        $expense->save();

        flash()->success('Expense has been created');

        return redirect()->back();
    }

    public function markPaid(ExpensePayment $expensePayment)
    {
        $expensePayment->paid = true;
        $expensePayment->save();

        flash()->success('Expense has been marked as paid');

        return redirect()->back();
    }
}
