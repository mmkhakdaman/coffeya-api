<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Customer\Entities\Address;

class OrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'cart' => 'required|array',
            'cart.*.product_id' => 'required|integer|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',

            'is_delivery' => 'required|boolean',
            'description' => 'nullable|string',

            'table_id' => 'nullable|integer|exists:tables,id',
            'address_id' => ['required_if:is_delivery,true|integer', Rule::exists(Address::class, 'id')->where('customer_id', auth('customer')->id())],

            'is_packaging' => 'sometimes|required_if:is_delivery,false|boolean',

            'is_pay_in_restaurant' => ['required_if:is_delivery,false|boolean'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth('customer')->check() === true;
    }
}
