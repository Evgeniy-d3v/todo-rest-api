<?php

namespace App\Presentation\Requests;

use App\Domain\Entities\TaskPriorityEnum;
use App\Domain\Entities\TaskStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class StoreTaskRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', 'string', new Enum(TaskStatusEnum::class)],
            'priority' => ['required', 'string', new Enum(TaskPriorityEnum::class)],
            'due_at' => ['required', 'string'],
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation failed. ' . '('.$validator->errors()->first().')',
            ], 422)
        );
    }
}
