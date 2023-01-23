<?php

namespace App\Enums;

enum ExpenseType: string
{
    case ONE_TIME = 'one_time';
    case MONTHLY = 'monthly';
    case SELECTED_MONTHS = 'selected_months';
    case YEARLY = 'yearly';

    public static function values(): array
    {
        return collect(self::cases())
            ->map(fn (self $type) => $type->value)
            ->toArray();
    }
}
