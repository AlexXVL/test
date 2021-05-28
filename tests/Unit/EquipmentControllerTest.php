<?php

namespace Tests\Unit;

use App\Models\Equipment;
use Illuminate\Http\Response;
use Tests\TestCase;

class EquipmentControllerTest extends TestCase {

    public function testIndexReturnsDataInValidFormat()
    {

        $this->json('get', 'api/equipment')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'equipment_type_id',
                            'serial_number',
                            'comment',
                        ]
                    ]
                ]
            );
    }


    public function testEquipmentIsCreatedSuccessfully()
    {

        $payload = [
            'serial_number'     => 'A2FGNBT6FR',
            'comment'           => 'bla bla'
        ];
        $this->json('post', 'api/equipment', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'equipment_type_id',
                        'serial_number',
                        'comment',
                    ]
                ]
            );
        $this->assertDatabaseHas('equipments', $payload);
    }

    /**
     * Проверка на дублирование записи
     */
    public function testEquipmentIsDoubleCreatedFailed()
    {
        $payload= [
            'serial_number'     => 'A2FGNBT6FR',
            'comment'           => 'bla bla'
        ];

        $this->json('post', 'api/equipment', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'equipment_type_id',
                        'serial_number',
                        'comment',
                    ]
                ]
            );
        $this->assertDatabaseHas('equipments', $payload);

        $this->json('post', 'api/equipment', $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(
                [
                    'errors' => [
                        'serial_number'
                    ]
                ]
            );
    }


    public function testEquipmentIsShownCorrectly()
    {
        $equipment= Equipment::create(
            [
                'serial_number'     =>  'A2FGNBT7SR',
                'equipment_type_id' => '2',
                'comment'           => $this->faker->firstName,
            ]
        );


        $this->json('get', "api/equipment/$equipment->id")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                [
                    'data' => [
                        'id'                => $equipment->id,
                        'equipment_type_id' => $equipment->equipment_type_id,
                        'serial_number'     => $equipment->serial_number,
                        'comment'           => $equipment->comment,
                    ]
                ]
            );
    }


    public function testEquipmentIsDestroyed()
    {
        $equipmentData=
            [
                'serial_number'     =>  'A3FGNBT7SR',
                'equipment_type_id' => '2',
                'comment'           => $this->faker->firstName,
            ];

        $equipment= Equipment::create($equipmentData);

        $this->json('delete', "api/equipment/$equipment->id")->assertNoContent();
        $this->assertDatabaseMissing('equipments', $equipmentData);
    }


    public function testUpdateEquipmentReturnsCorrectData()
    {
        $equipment= Equipment::create(
            [
                'serial_number'     =>  'A2FGNBT7SR',
                'equipment_type_id' => 1,
                'comment'           => $this->faker->firstName,
            ]
        );

        $payload= [
            'serial_number' => 'A2FGNBT7ER',
            'comment'       => $this->faker->firstName,
        ];

        $this->json('put', "api/equipment/$equipment->id", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                [
                    'data' => [
                        'id'                => $equipment->id,
                        'equipment_type_id' => 1,
                        'serial_number'  => $payload['serial_number'],
                        'comment'      => $payload['comment'],
                    ]
                ]
            );
    }
}
