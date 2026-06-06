<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $categoryId = $this->route('category')->id;

        return [
           'name' => [
            'required',
            'string',
            'min:2',
            'max:255',
            'unique:categories,name,' . $categoryId,
            'regex:/^[a-zA-Z0-9\s\-_]+$/',
        ],
            'description' => 'nullable|string|max:1000',
            'is_active'   => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required.',
            'name.unique'   => 'This category name already exists.',
            'name.min'      => 'Category name must be at least 2 characters.',
            'name.max'      => 'Category name cannot exceed 255 characters.',
            'name.regex'    => 'Category name may only contain letters, spaces, and hyphens.',
        ];
    }
}
