<?php

namespace App\Http\Controllers;

use App\Http\Requests\Income\StoreIncomeRequest;
use App\Models\Income;
use Inertia\Inertia;

class IncomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Income/Index');
    }

    public function store(StoreIncomeRequest $request)
    {
        $income = new Income();
        $income->user_id = auth()->id();
        $income->name = $request->string('name');
        $income->value = $request->float('value');
        $income->save();

        return Inertia::location(route('dashboard'));
    }
}
