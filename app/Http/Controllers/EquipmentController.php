<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentRequest;
use App\Models\Equipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Rules\SerialNumberUniqueRule;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $e= Equipment::all();
        return response()->json(['data'=> $e], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EquipmentRequest  $request
     * @return array|JsonResponse
     */
    public function store(EquipmentRequest $request)
    {
        $validated= $request->validated();
//        $request->validate([
//            'serial_number' => ['required', 'string', new SerialNumberUniqueRule()],
//        ]);

        $equipment_type_id= Equipment::getEquipmentTypeIdFromSerialNumber($validated['serial_number']);
        if ($equipment_type_id)
        {
            $validated['equipment_type_id']= $equipment_type_id;
            $e= Equipment::create($validated);
            return response()->json(['data'=> $e], 201);
        }
        else
            return response()->json(['errors' => ['equipment_type_id' => ['Не найден тип устройства']]], 422); //400
    }

    /**
     * Display the specified resource.
     *
     * @todo надо узнать какой статус правильный при ошибке, пока что поставил 404
     * @param  int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $e= Equipment::find($id);
        if(is_null($e))
            return response()->json(['errors' => ['Оборудование не найдено']], 404);

        return response()->json(['data'=> $e], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @todo надо узнать какой статус правильный при ошибке, пока что поставил 404
     * @param  Request  $request
     * @param  int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $e= Equipment::find($id);
        if(is_null($e))
            return response()->json(['errors' => ['Оборудование не найдено']], 404);

        if (!is_null($request->input('serial_number')))
        {
            if (!Equipment::where('serial_number', $request->input('serial_number'))->where('id', '<>', $id)->count())
            {
                $e->serial_number= $request->input('serial_number');

                // если изменился серийный номер, то надо найти соответствующий тип оборудования
                $equipment_type_id= Equipment::getEquipmentTypeIdFromSerialNumber($request->input('serial_number'));
                if ($equipment_type_id)
                    $e->equipment_type_id= $equipment_type_id;
                else
                    return response()->json(['errors' => ['equipment_type_id' => ['Не найден тип устройства']]], 422);
            }
        }


        if (!is_null($request->input('comment')))
            $e->comment= $request->input('comment');

        $e->save();
        return response()->json(['data'=> $e], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @todo надо узнать какой статус правильный при ошибке, пока что поставил 404
     * @param  int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $e= Equipment::find($id);
        if(is_null($e))
            return response()->json(['errors' => ['Оборудование не найдено']], 404);

        if ($e->delete())
            return response()->json(null, 204);
        else
            return response()->json(['errors' => ['Не удалось удалить оборудование']], 500);
    }
}
