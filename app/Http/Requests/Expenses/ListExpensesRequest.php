<?php

namespace App\Http\Requests\Expenses;

use App\Enums\ExpenseCategory;
use App\Enums\ExpensePaymentType;
use Illuminate\Foundation\Http\FormRequest;

class ListExpensesRequest extends FormRequest
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
            'perPage' => ['nullable', 'numeric', 'in:10,15,30,50,100,500'],
            'page' => ['nullable', 'numeric', 'gte:1'],
            'sortField' => ['nullable', 'string', 'in:start_date,end_date,id,name,value,category,payment_type'],
            'sortOrder' => ['nullable', 'string', 'in:asc,desc'],
            'filters.name' => ['sometimes', 'string'],
            'filters.value.min' => ['sometimes', 'numeric', 'gt:0'],
            'filters.value.max' => ['sometimes', 'numeric', 'gt:0'],
            'filters.payment_type' => ['sometimes', 'string', 'in:' . implode(',', ExpensePaymentType::values())],
            'filters.category' => ['sometimes', 'string', 'in:' . implode(',', ExpenseCategory::values())],
            'filters.status' => ['sometimes', 'string', 'in:all,active,inactive'],
            'filters.start_date.min' => ['sometimes', 'date'],
            'filters.start_date.max' => ['sometimes', 'date'],
            'filters.end_date.min' => ['sometimes', 'date'],
            'filters.end_date.max' => ['sometimes', 'date'],
        ];
    }
}
