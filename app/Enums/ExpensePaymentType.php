<?php

namespace App\Enums;

enum ExpensePaymentType: string
{
    case CASH = 'cash';
    case CREDIT_CARD = 'credit_card';
    case ONLINE = 'online';

    public static function options(): array
    {
        return collect(self::cases())
            ->map(fn (self $case) => [
                'value' => $case->value,
                'label' => match($case) {
                    self::CASH => "Cash",
                    self::CREDIT_CARD => "Credit Card",
                    self::ONLINE => "Online Payment",
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
