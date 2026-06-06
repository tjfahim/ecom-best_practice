<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SubcategoryUpdateRequest extends FormRequest
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
        $subcategoryId = $this->route('subcategory')->id;

        return [
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'unique:subcategories,name,' . $subcategoryId,
                'regex:/^[a-zA-Z0-9\s\-_]+$/',
            ],
            'description' => 'nullable|string|max:1000',
            'is_active'   => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a category.',
            'category_id.exists'   => 'Selected category does not exist.',
            'name.required'        => 'Subcategory name is required.',
            'name.unique'          => 'This subcategory name already exists.',
            'name.min'             => 'Subcategory name must be at least 2 characters.',
            'name.max'             => 'Subcategory name cannot exceed 255 characters.',
            'name.regex'           => 'Subcategory name may only contain letters, spaces, and hyphens.',
        ];
    }
}
