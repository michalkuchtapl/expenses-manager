<?php

namespace App\Http\Controllers;

use App\Http\Requests\Income\ListIncomeRequest;
use App\Http\Requests\Income\StoreIncomeRequest;
use App\Http\Resources\IncomeResource;
use App\Models\Income;
use Inertia\Inertia;

class IncomeController extends Controller
{
    public function list(ListIncomeRequest $request)
    {
        $incomes = Income::query()
            ->whereUserId(auth()->id())
            ->orderBy($request->get('sortField', 'created_at'), $request->get('sortOrder', 'asc'))
            ->paginate(
                perPage: $request->get('perPage'),
                page: $request->get('page'),
            );

        return Inertia::render('Income/List', [
            'incomes' => IncomeResource::collection($incomes->items()),
            'pageInfo' => [
                "first" => $incomes->firstItem(),
                "perPage" => $incomes->perPage(),
                "sortField" => $request->get('sortField', 'created_at'),
                "sortOrder" => $request->get('sortOrder', 'asc'),
                'total' => $incomes->total()
            ]
        ]);
    }

    public function store(StoreIncomeRequest $request)
    {
        $income = new Income();
        $income->user_id = auth()->id();
        $income->name = $request->string('name');
        $income->value = $request->float('value');
        $income->save();

        flash()->success('Income has been created');

        return Inertia::location(route('dashboard'));
    }
}
