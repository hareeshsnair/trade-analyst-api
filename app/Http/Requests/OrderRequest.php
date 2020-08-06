<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\OptionOrderTypeRule;
use App\Rules\InstrumentTypeRule;
use App\Rules\IsFutureDateRule;
use App\Rules\WeekDayRule;

class OrderRequest extends FormRequest
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
            'stock_id' => 'required|exists:listed_companies,id',
            'exchange_id' => 'required|exists:stock_exchanges,id',
            'instrument_type_id' => 'required|exists:instrument_types,id',
            'trade_type_id' => 'required|exists:trade_types,id',
            'expiry_date' => 'required_if:instrument_type_id,2,3|date_format:Y-m-d',
            'strike_price' => 'required_if:instrument_type_id,3|numeric',
            'option_type' => [
                    'required_if:instrument_type_id,3', 
                    new OptionOrderTypeRule
                ],
            'order_type' => [
                    'required',
                    new OptionOrderTypeRule,
                ],
            'price' => 'required|numeric',
            'qty' => 'required|integer',
            'is_mtf_opted' => 'required|boolean',
            'bought_on' => [
                    'required_if:order_type,b',
                    'date_format:Y-m-d',
                    new IsFutureDateRule,
                    new WeekDayRule,
                ],
            'sold_on' => [
                    'required_if:order_type,s',
                    'date_format:Y-m-d',
                    new IsFutureDateRule,
                    new WeekDayRule,
                ],
        ];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function messages()
    {
        return [
            'bought_on.required_if' => 'Please select Bought On date',
            'sold_on.required_if' => 'Please select Sold On date'
        ];
    }
}
