<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cart' => 'required|array',
            'cart.*.product_id' => 'required|integer|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'table_id' => 'required|integer|exists:tables,id'
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
