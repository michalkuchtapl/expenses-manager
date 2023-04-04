<?php

namespace App\Http\Requests\Income;

use Illuminate\Foundation\Http\FormRequest;

class ListIncomeRequest extends FormRequest
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
            'sortField' => ['nullable', 'string', 'in:created_at,id,name,value'],
            'sortOrder' => ['nullable', 'string', 'in:asc,desc']
        ];
    }
}
