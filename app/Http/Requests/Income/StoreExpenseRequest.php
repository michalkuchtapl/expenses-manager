<?php

namespace App\Http\Requests\Income;

use App\Enums\ExpenseType;
use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
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
            'value' => ['required', 'numeric'],
            'type' => ['required', 'in:'.implode(',', ExpenseType::values())],
            'day' => ['exclude_unless:type,one_time', 'required_unless:type,one_time', 'between:1,31', 'numeric'],
            'month' => ['exclude_unless:type,yearly', 'required_if:type,yearly', 'in:01,02,03,04,05,06,07,08,09,10,11,12'],
            'months' => ['exclude_unless:type,selected_months', 'required_if:type,selected_months', 'min:1'],
            'repetitions' => ['required', 'numeric', 'min:1'],
        ];
    }
}
