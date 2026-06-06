<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
        $productId = $this->route('product')->id;

        return [
            'subcategory_id' => [
                'required',
                'exists:subcategories,id',
            ],
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'unique:products,name,' . $productId,
                'regex:/^[a-zA-Z0-9\s\-_]+$/',
            ],
            'description' => 'nullable|string|max:2000',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'old_price'   => 'nullable|numeric|min:0',
            'new_price'   => 'required|numeric|min:0',
            'is_active'   => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'subcategory_id.required' => 'Please select a subcategory.',
            'subcategory_id.exists'   => 'Selected subcategory does not exist.',
            'name.required'           => 'Product name is required.',
            'name.unique'             => 'This product name already exists.',
            'name.min'                => 'Product name must be at least 2 characters.',
            'name.max'                => 'Product name cannot exceed 255 characters.',
            'name.regex'              => 'Product name may only contain letters, numbers, spaces, and hyphens.',
            'image.image'             => 'The file must be an image.',
            'image.mimes'             => 'Image must be jpeg, png, jpg, or webp.',
            'image.max'               => 'Image size cannot exceed 2MB.',
            'old_price.numeric'       => 'Old price must be a number.',
            'new_price.required'      => 'New price is required.',
            'new_price.numeric'       => 'New price must be a number.',
            'new_price.min'           => 'New price cannot be negative.',
        ];
    }
}
