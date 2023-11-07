<?php

namespace Modules\Tenant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TenantRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'logo' => ['sometimes', 'image', 'max:2048'],
            'phone' => ['sometimes', 'digits:11'],
            'address' => ['sometimes', 'string', 'max:1000'],
            'location' => ['sometimes', 'string', 'max:1000'],
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
