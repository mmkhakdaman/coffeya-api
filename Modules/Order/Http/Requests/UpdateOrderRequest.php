<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Customer\Entities\Address;
use Modules\Order\Enums\OrderStatusEnum;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in(
                    get_value_enums(
                        OrderStatusEnum::cases()
                    )
                ),
            ],
            'complete_at' => 'sometimes|date_format:Y-m-d H:i',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth('tenant_admin')->check();
    }
}
