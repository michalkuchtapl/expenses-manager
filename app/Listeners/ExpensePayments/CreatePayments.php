<?php

namespace App\Listeners\ExpensePayments;

use App\Enums\ExpenseType;
use App\Events\Expenses\ExpenseCreated;
use App\Models\Expense;
use App\Models\ExpensePayment;
use Carbon\Carbon;

class CreatePayments
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(ExpenseCreated $event)
    {
        $expense = $event->expense;

        if ($expense->type == ExpenseType::ONE_TIME) {
            $this->handleOneTime($expense);
        } elseif ($expense->type == ExpenseType::MONTHLY) {
            $this->handleMonthly($expense);
        } elseif ($expense->type == ExpenseType::SELECTED_MONTHS) {
            $this->handleMonths($expense);
        } elseif ($expense->type == ExpenseType::YEARLY) {
            $this->handleYarly($expense);
        }
    }

    protected function handleOneTime(Expense $expense)
    {
        $payment = new ExpensePayment();
        $payment->value = $expense->value;
        $payment->due_date = $expense->start_date;

        $expense
            ->payments()
            ->save($payment);
    }

    protected function handleMonthly(Expense $expense)
    {
        /** @var Carbon $date */
        $date = $expense->start_date;

        while ($date->isBefore($expense->end_date) || $date->isSameMonth($expense->end_date)) {
            $payment = new ExpensePayment();
            $payment->value = $expense->value;
            $payment->due_date = $date;

            $expense
                ->payments()
                ->save($payment);

            $date = $payment->due_date->startOfMonth()->addMonth();

            if ($expense->start_date->day > $date->daysInMonth) {
                $date = $date->endOfMonth();
            } else {
                $date = $date->setDay($expense->start_date->day);
            }
        }
    }

    protected function handleMonths(Expense $expense)
    {
        $date = $expense->start_date;

        $months = $expense->months;
        sort($months);

        while ($date->isBefore($expense->end_date) || $date->isSameMonth($expense->end_date)) {
            foreach ($months as $month) {
                $date = $date->startOfMonth()->setMonth($month);

                if ($expense->start_date->day > $date->daysInMonth) {
                    $date = $date->endOfMonth();
                } else {
                    $date = $date->setDay($expense->start_date->day);
                }

                if ($date->isBefore($expense->start_date) || $date->isAfter($expense->end_date)) {
                    continue;
                }

                $payment = new ExpensePayment();
                $payment->value = $expense->value;
                $payment->due_date = $date;

                $expense
                    ->payments()
                    ->save($payment);
            }
            $date = $date->startOfYear()->addYear();
        }
    }

    protected function handleYarly(Expense $expense)
    {
        /** @var Carbon $date */
        $date = $expense->start_date;

        while ($date->isBefore($expense->end_date) || $date->isSameYear($expense->end_date)) {
            $payment = new ExpensePayment();
            $payment->value = $expense->value;
            $payment->due_date = $date;

            $expense
                ->payments()
                ->save($payment);

            $date = $payment->due_date->startOfMonth()->addYear();

            if ($expense->start_date->day > $date->daysInMonth) {
                $date = $date->endOfMonth();
            } else {
                $date = $date->setDay($expense->start_date->day);
            }
        }
    }
}
