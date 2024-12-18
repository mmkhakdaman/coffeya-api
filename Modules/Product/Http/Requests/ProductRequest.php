<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => [
                $this->product ? 'nullable' : 'required',
                'exists:categories,id'
            ],
            'title' => 'required|string|max:255|unique:products,title,' . $this->product?->id,
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|image',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
