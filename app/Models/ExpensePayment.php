<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ExpensePayment
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $expense_id
 * @property string $due_date
 * @property float $value
 * @property int $paid
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ExpensePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpensePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpensePayment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpensePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpensePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpensePayment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpensePayment whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpensePayment whereExpenseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpensePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpensePayment wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpensePayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpensePayment whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpensePayment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpensePayment withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ExpensePayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
