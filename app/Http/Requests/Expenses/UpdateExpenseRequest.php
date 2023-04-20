<?php

namespace App\Http\Requests\Expenses;

use App\Enums\ExpenseCategory;
use App\Enums\ExpensePaymentType;
use App\Enums\ExpenseType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'value' => ['required', 'numeric', 'gt:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'category' => ['required', 'string', 'in:' . implode(',', ExpenseCategory::values())],
            'payment_type' => ['required', 'string', 'in:' . implode(',', ExpensePaymentType::values())]
        ];
    }
}
