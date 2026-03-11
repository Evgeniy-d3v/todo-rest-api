<?php

namespace App\Presentation\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SyncTagsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tagsIdList' => ['required', 'array', 'max:255'],
            'tagsIdList.*' => ['integer', 'distinct', 'exists:tags,id'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation failed. (' . $validator->errors()->first() . ')',
            ], 422)
        );
    }
}
