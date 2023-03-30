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
        $upcomingExpenses = Expense::getUpcomingExpenses(limit: 5);

        return Inertia::render('Dashboard', [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'upcomingExpenses' => $upcomingExpenses,
        ]);
    }

    public function getStatistics()
    {
        $expenseStats = Expense::getStatisticsForYear();
        $incomeStats = Income::getStatisticsForYear();

        $stats = [
            'income' => [],
            'expanse' => [],
            'gross' => [],
        ];

        for ($i = 1; $i <= 12; $i++) {
            $stats['income'][] = $incomeStats->get($i, 0);
            $stats['expanse'][] = $expenseStats->get($i, 0);
            $stats['gross'][] = $incomeStats->get($i, 0) - $expenseStats->get($i, 0);
        }

        return response()->json([
            'statistics' => array_values($stats),
        ]);
    }
}
