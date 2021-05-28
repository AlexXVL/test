<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentType extends Model
{
    use HasFactory;

    protected $table= 'equipments_types';

    public static function convert_mask_to_regexp_pattern($mask)
    {
        $result= '';
        $arr=[
            'N'=> '\d',
            'A'=> '[A-Z]',
            'a'=> '[a-z]',
            'X'=> '[A-Z0-9]',
            'Z'=> '(-|_|@)',
        ];

        $length= strlen($mask);
        for ($i= 0; $i< $length; $i++)
            $result.= $arr[$mask[$i]];

        return '/'.$result.'/';
    }
}
