<?php

namespace App\Http\Requests;

use App\Rules\IntegerArray;
use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
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
            'title' => 'string|required',
            'body' => 'array|required',
            'user_ids' => [
                'array',
                'required',
                new IntegerArray(),
//                function ($attribute, $value, $fail) {
//                    $integerOnly = collect($value)->every(fn($el) => is_int($el));
//                    if (!$integerOnly) {
//                        $fail($attribute . ' can only be integers.');
//                    }
//                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'Title should be a string',
            'body.required' => 'Please enter a value for body',
        ];
    }
}
