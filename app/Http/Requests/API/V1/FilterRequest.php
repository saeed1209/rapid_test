<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
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
            'company_symbol'=>'required',
            'start_date'=>'required|date_format:Y-m-d|before_or_equal:end_date|before_or_equal:now',
            'end_date'=>'required|date_format:Y-m-d|after_or_equal:start_date|before_or_equal:now',
            'email'=>'required|email:rfc,dns'
        ];
    }
}
