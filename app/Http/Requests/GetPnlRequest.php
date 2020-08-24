<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetPnlRequest extends FormRequest
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
            'filter_by' => 'sometimes|string',
            'prev' => 'sometimes|integer'
        ];
    }

    public function messages()
    {
        return [
            'prev.integer' => 'Invalid filter data passed',
            'filter_by.string' => 'Invalid filter data passed'
        ];
    }
}
