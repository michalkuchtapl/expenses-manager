<?php

namespace App\Http\Controllers;

use App\Enums\ExpenseType;
use App\Http\Requests\Income\StoreExpenseRequest;
use App\Models\Expense;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    public function index()
    {
        return Inertia::render('Expense/Index');
    }

    public function store(StoreExpenseRequest $request)
    {
        $expense = new Expense();
        $expense->user_id = auth()->id();
        $expense->type = ExpenseType::from($request->string('type'));
        $expense->name = $request->string('name');
        $expense->value = $request->float('value');
        $expense->day = $request->integer('day');
        $expense->month = $request->integer('month');
        $expense->months = $request->input('months');
        $expense->repetitions = $request->integer('repetitions');
        $expense->save();

        return response()->json([
            'expense' => $expense,
        ]);
    }
}
