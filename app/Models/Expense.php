<?php

namespace App\Models;

use App\Enums\ExpenseType;
use App\Events\Expenses\ExpenseCreated;
use App\Events\Expenses\ExpenseDeleted;
use App\Events\Expenses\ExpenseUpdated;
use App\Http\Requests\Expenses\ListExpensesRequest;
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
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $payment_type
 * @property string $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExpensePayment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\User $user
 * @method static Builder|Expense newModelQuery()
 * @method static Builder|Expense newQuery()
 * @method static Builder|Expense onlyTrashed()
 * @method static Builder|Expense query()
 * @method static Builder|Expense whereCategory($value)
 * @method static Builder|Expense whereCreatedAt($value)
 * @method static Builder|Expense whereDay($value)
 * @method static Builder|Expense whereDeletedAt($value)
 * @method static Builder|Expense whereEndDate($value)
 * @method static Builder|Expense whereId($value)
 * @method static Builder|Expense whereMonth($value)
 * @method static Builder|Expense whereMonths($value)
 * @method static Builder|Expense whereName($value)
 * @method static Builder|Expense wherePaymentType($value)
 * @method static Builder|Expense whereStartDate($value)
 * @method static Builder|Expense whereType($value)
 * @method static Builder|Expense whereUpdatedAt($value)
 * @method static Builder|Expense whereUserId($value)
 * @method static Builder|Expense whereValue($value)
 * @method static Builder|Expense withTrashed()
 * @method static Builder|Expense withoutTrashed()
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
        'end_date' => 'datetime',
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

    public static function filters(ListExpensesRequest $request) {
        $query = self::query();

        $request->whenFilled('filters.name', fn () => $query->where('name', 'like', "%".$request->get('filters.name')."%"));
        $request->whenFilled('filters.value.min', fn () => $query->where('value', '>=', $request->get('filters.value.min')));
        $request->whenFilled('filters.value.max', fn () => $query->where('value', '<=', $request->get('filters.value.max')));
        $request->whenFilled('filters.payment_type', fn () => $query->where('payment_type', $request->get('filters.payment_type')));
        $request->whenFilled('filters.category', fn () => $query->where('category', $request->get('filters.category')));
        $query->when(
            $request->filled('filters.status') && $request->get('filters.status') == 'active',
            fn($query) => $query->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())
        );
        $query->when(
            $request->filled('filters.status') && $request->get('filters.status') == 'inactive',
            fn($query) => $query->orWhere('start_date', '>', Carbon::now())->orWhere('end_date', '<', Carbon::now())
        );

        $request->whenFilled('filters.start_date.min', fn () => $query->where('start_date', '>=', $request->date('filters.start_date.min')));
        $request->whenFilled('filters.start_date.max', fn () => $query->where('start_date', '<=', $request->date('filters.start_date.max')));
        $request->whenFilled('filters.end_date.min', fn () => $query->where('end_date', '>=', $request->date('filters.end_date.min')));
        $request->whenFilled('filters.end_date.max', fn () => $query->where('end_date', '<=', $request->date('filters.end_date.max')));

        return $query;
    }
}
