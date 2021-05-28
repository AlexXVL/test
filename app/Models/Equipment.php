<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $table= 'equipments';

    protected $fillable= ['serial_number', 'equipment_type_id', 'comment'];

    protected $hidden= ['created_at', 'updated_at'];

    protected $casts = [
        'id' => 'integer',
        'equipment_type_id'=> 'integer',
        'serial_number' => 'string',
        'comment' => 'string',
    ];


    /**
     * Возвращает equipment_type_id, найденный по серийному номеру, либо null
     *
     * @param string $serial_number
     * @return int|null
     */
    public static function getEquipmentTypeIdFromSerialNumber(string $serial_number)
    {
        $serial_number_masks= EquipmentType::select(['id', 'serial_number_mask'])->get();

        foreach ($serial_number_masks as $item)
        {
            if (strlen($serial_number) !== strlen($item->serial_number_mask))
                continue;

            $pattern= EquipmentType::convert_mask_to_regexp_pattern($item->serial_number_mask);
            if (preg_match($pattern, $serial_number))
                return $item->id;
        }
        return null;
    }
}
