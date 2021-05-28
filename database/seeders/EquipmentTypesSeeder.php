<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('equipments_types')->insert(
            [
                [
                'name' => 'TP-Link TL-WR74',
                'serial_number_mask' => 'XXAAAAAXAA',
                ],
                [
                    'name' => 'D-Link DIR-300',
                    'serial_number_mask' => 'NXXAAXZXaa',
                ],
                [
                    'name' => 'D-Link DIR-300 S',
                    'serial_number_mask' => 'NXXAAXZXXX',
                ],
            ]
        );
    }
}
