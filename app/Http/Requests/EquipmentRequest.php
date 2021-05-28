<?php

namespace App\Http\Requests;

use App\Models\EquipmentType;
use Illuminate\Foundation\Http\FormRequest;

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


//    public function withValidator($validator)
//    {
//        if(!$validator->fails())
//        {
//            $validator->after(function ($validator)
//            {
//                if( is_null($id= $this->find_serial_number_mask($this->input('serial_number'))) )
//                {
//                    $validator->errors()->add('serial_number', 'Не найдена маска серийного номера!');
//                }
//            });
//        }
//    }
//
//
//    /**
//     * @param string $serial_number
//     * @return int|null
//     */
//    private function find_serial_number_mask(string $serial_number)
//    {
//        $serial_number_masks= EquipmentType::select(['id', 'serial_number_mask'])->get();
//
//        foreach ($serial_number_masks as $item)
//        {
//            if (strlen($serial_number) !== strlen($item->serial_number_mask))
//                continue;
//
//            $pattern= EquipmentType::convert_mask_to_regexp_pattern($item->serial_number_mask);
//            if (preg_match($pattern, $serial_number))
//            {
//                return $item->id;
//            }
//
//        }
//        return null;
//    }
}
