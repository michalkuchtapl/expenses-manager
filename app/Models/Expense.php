<?php

namespace App\Models;

use App\Enums\ExpenseType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Expense
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property string $name
 * @property float $value
 * @property string $type
 * @property int|null $month
 * @property mixed|null $months
 * @property int|null $day
 * @property int|null $repetitions
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense query()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereMonths($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereRepetitions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereValue($value)
 *
 * @mixin \Eloquent
 */
class Expense extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => ExpenseType::class,
        'months' => 'array'
    ];

    public static function getTotalExpense(?int $year = null, ?int $month = null, ?int $day = null): float
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        $day = $day ?? now()->day;

        return self::query()
            ->where(function () {
                
            })
            ->orWhere(function () {

            })
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('value');
    }
}
