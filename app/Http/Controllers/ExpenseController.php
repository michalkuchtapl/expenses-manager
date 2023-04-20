<?php

namespace App\Http\Controllers;

use App\Enums\ExpenseCategory;
use App\Enums\ExpensePaymentType;
use App\Enums\ExpenseType;
use App\Http\Requests\Expenses\ListExpensesRequest;
use App\Http\Requests\Expenses\StoreExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Models\ExpensePayment;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    public function list(ListExpensesRequest $request)
    {
        $expenses = Expense::filters($request)
            ->whereUserId(auth()->id())
            ->orderBy($request->get('sortField', 'id'), $request->get('sortOrder', 'asc'))
            ->paginate(
                perPage: $request->get('perPage'),
                page: $request->get('page'),
            );

        return Inertia::render('Expenses/List', [
            'expenses' => ExpenseResource::collection($expenses->items()),
            'pageInfo' => [
                "first" => $expenses->firstItem(),
                "perPage" => $expenses->perPage(),
                "sortField" => $request->get('sortField', 'created_at'),
                "sortOrder" => $request->get('sortOrder', 'asc'),
                'total' => $expenses->total()
            ]
        ]);
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

    public function update(StoreExpenseRequest $request)
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

        flash()->success('Expense has been updated');

        return redirect()->route('expense.list');
    }

    public function payments()
    {
        $year = request()->integer('year', Carbon::now()->year);

        $expenses = Expense::query()
            ->with([
                'payments' => fn ($query) => $query->whereYear('due_date', $year)
            ])
            ->whereUserId(auth()->id())
            ->whereHas('payments', fn ($query) => $query->whereYear('due_date', $year))
            ->where('type', '!=', ExpenseType::ONE_TIME)
            ->get()
            ->map(function (Expense $expense) {
                $payments = $expense->payments
                    ->mapWithKeys(fn (ExpensePayment $expensePayment) => [
                        $expensePayment->due_date->month => [
                            'due_date' => $expensePayment->due_date->toDateString(),
                            'paid' => $expensePayment->paid,
                            'applicable' => true,
                            'pastDueDate' => $expensePayment->due_date->isPast(),
                            'value' => $expensePayment->value,
                            'enabled' => $expensePayment->enabled,
                        ]
                    ]);

                for ($i = 1; $i <= 12; $i++) {
                    if (!$payments->has($i)) {
                        $payments->put($i, [
                            'due_date' => null,
                            'paid' => false,
                            'applicable' => false,
                            'pastDueDate' => false,
                            'value' => 0,
                            'enabled' => false,
                        ]);
                    }
                }

                return [
                    'id' => $expense->id,
                    'name' => $expense->name,
                    'value' => $expense->value,
                    'type' => $expense->type,
                    'payments' => $payments,
                ];
            });

        return response()->json([
            'expenses' => $expenses,
        ]);
    }
}
