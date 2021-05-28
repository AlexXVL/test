<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\SerialNumberUniqueRule;

class EquipmentRequest extends FormRequest
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
            'serial_number'=> 'required',
            'comment'=> ''
        ];
    }


    public function withValidator($validator)
    {
        if(!$validator->fails())
        {
            $validator->after(function ($validator)
            {
                $this->validate([
                    'serial_number' => ['required', 'string', new SerialNumberUniqueRule()],
                ]);
            });
        }
    }
}
