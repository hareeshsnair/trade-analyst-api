<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class OptionOrderTypeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $attribute === 'option_type' ? in_array($value, ['ce','pe']) : in_array($value, ['b','s']);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid :attribute passed.';
    }
}
