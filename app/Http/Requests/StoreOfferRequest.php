<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckProductsNumberUniqueness;

class StoreOfferRequest extends FormRequest
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
            'offers' => ['required', 'array', new CheckProductsNumberUniqueness],
            'offers.*.products_number' => 'required|numeric|min:2',
            'offers.*.price' => 'required|numeric',
        ];
    }
}
