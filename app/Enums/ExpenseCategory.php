<?php

namespace App\Enums;

enum ExpenseCategory: string
{
    case GROCERY = 'cash';
    case FOOD = 'food';
    case CLOTHES = 'clothes';
    case CAR = 'car';
    case ENTERTAINMENT = 'entertainment';
    case HEALTHCARE = 'healthcare';
    case LOANS = 'loans';
    case UTILITIES = 'utilities';
    case OTHER = 'other';

    public static function options(): array
    {
        return collect(self::cases())
            ->map(fn (self $case) => [
                'value' => $case->value,
                'label' => match($case) {
                    self::GROCERY => "Grocery",
                    self::FOOD => "Food",
                    self::CLOTHES => "Clothes",
                    self::CAR => "Car",
                    self::ENTERTAINMENT => "Entertainment",
                    self::HEALTHCARE => "Healthcare",
                    self::LOANS => "Loans",
                    self::UTILITIES => "Utilities",
                    self::OTHER => "Other",
                },
            ])
            ->toArray();
    }

    public static function values(): array
    {
        return collect(self::cases())
            ->map(fn (self $type) => $type->value)
            ->toArray();
    }
}
