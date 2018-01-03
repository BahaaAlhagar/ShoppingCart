<?php

namespace App\Http\Requests\ShoppingCart;

use Illuminate\Foundation\Http\FormRequest;

class validateCheckOutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'address' => 'required|string',
            'card-name' => 'required|string',
            'card-number' => 'required|numeric',
            'card-expiry-month' => 'required|numeric|max:2',
            'card-expiry-year' => 'required|numeric|min:4|max:4',
            'card-cvc' => 'required|numeric|min:3|max:3'
        ];
    }
}
