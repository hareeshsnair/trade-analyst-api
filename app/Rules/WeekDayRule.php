<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class WeekDayRule implements Rule
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
        $dt = Carbon::create($value);
        return $dt->isWeekday();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':Attribute date is invalid';
    }
}
