<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Enums\TransactionType;

class BalanceRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'from_date' => 'nullable|date_format:Y-m-d',
            'to_date' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:from_date',
            ],
            'type' => [
                'nullable',
                'string',
                'in:' . implode(',', TransactionType::getValues()),
            ],
            'page' => 'nullable|integer|min:1',
            'perPage' => 'nullable|integer|min:1|max:100',
        ];
    }
}
