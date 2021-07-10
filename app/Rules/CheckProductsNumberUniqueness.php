<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckProductsNumberUniqueness implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $productsNumbers = [];
        try {
            foreach ($value as $offerData) {
                $productsNumbers[] = $offerData['products_number'];
            }
        } catch (\Exception $e) {
            return false;
        }

        return count($productsNumbers) == count(array_unique($productsNumbers));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The value of products_number must be unique within the given request.';
    }
}
