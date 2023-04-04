<?php

namespace App\Models;

use App\Enums\ExpenseType;
use App\Events\Expenses\ExpenseCreated;
use App\Events\Expenses\ExpenseDeleted;
use App\Events\Expenses\ExpenseUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Expense
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property string $name
 * @property float $value
 * @property ExpenseType $type
 * @property int|null $month
 * @property array|null $months
 * @property int|null $day
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property string|null $end_date
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExpensePayment> $payments
 * @property-read int|null $payments_count
 *
 * @method static Builder|Expense newModelQuery()
 * @method static Builder|Expense newQuery()
 * @method static Builder|Expense onlyTrashed()
 * @method static Builder|Expense query()
 * @method static Builder|Expense whereCreatedAt($value)
 * @method static Builder|Expense whereDay($value)
 * @method static Builder|Expense whereDeletedAt($value)
 * @method static Builder|Expense whereEndDate($value)
 * @method static Builder|Expense whereId($value)
 * @method static Builder|Expense whereMonth($value)
 * @method static Builder|Expense whereMonths($value)
 * @method static Builder|Expense whereName($value)
 * @method static Builder|Expense whereStartDate($value)
 * @method static Builder|Expense whereType($value)
 * @method static Builder|Expense whereUpdatedAt($value)
 * @method static Builder|Expense whereUserId($value)
 * @method static Builder|Expense whereValue($value)
 * @method static Builder|Expense withTrashed()
 * @method static Builder|Expense withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $dispatchesEvents = [
        'created' => ExpenseCreated::class,
        'updated' => ExpenseUpdated::class,
        'deleted' => ExpenseDeleted::class,
    ];

    protected $casts = [
        'type' => ExpenseType::class,
        'months' => 'array',
        'start_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(ExpensePayment::class);
    }

    public static function getTotalExpense(?int $year = null, ?int $month = null): float
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;

        return ExpensePayment::query()
            ->whereHas('expense', fn ($query) => $query->where('user_id', auth()->id()))
            ->whereYear('due_date', $year)
            ->whereMonth('due_date', $month)
            ->sum('value');
    }

    public static function getUpcomingExpenses(?int $year = null, ?int $month = null, ?int $limit = 15)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;

        return ExpensePayment::query()
            ->with(['expense'])
            ->whereHas('expense', fn ($query) => $query
                ->where('user_id', auth()->id())
            )
            ->where(fn ($query) => $query
                ->orWhere(fn ($query) => $query
                    ->whereYear('due_date', $year)
                    ->whereMonth('due_date', $month)
                )
                ->orWhere(fn ($query) => $query
                    ->where('due_date', '<', Carbon::now())
                )
            )
            ->where('paid', 0)
            ->orderBy('due_date')
            ->limit($limit)
            ->get();
    }

    public static function getStatisticsForYear(?int $year = null)
    {
        return ExpensePayment::query()
            ->select([
                \DB::raw('SUM(value) as value'),
                \DB::raw('MONTH(due_date) as month'),
            ])
            ->whereHas('expense', fn ($query) => $query
                ->where('user_id', auth()->id())
            )
            ->whereYear('due_date', $year ?? Carbon::now()->year)
            ->groupBy([\DB::raw('MONTH(due_date)')])
            ->pluck('value', 'month');
    }
}
