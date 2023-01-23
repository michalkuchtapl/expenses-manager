<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalIncome = Income::getTotalIncome();
        $totalExpense = Expense::getTotalExpense();

        return Inertia::render('Dashboard', [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense
        ]);
    }
}
