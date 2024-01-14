<?php

namespace App\Http\Controllers\Sensor;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function index()
    {
        //$this->authorize('viewAny', Sensor::class);

        return Sensor::paginate()->all();
    }

    public function store(Request $request)
    {
        //$this->authorize('create', Sensor::class);

        $data = $request->validate([
            'room_id' => ['required', 'integer','exists:rooms,id','unique:sensors'],
        ]);
        $data["created_by"] = 1;

        return Sensor::create($data);
    }

    public function show(Sensor $sensor)
    {
        $this->authorize('view', $sensor);

        return $sensor;
    }

    public function update(Request $request, Sensor $sensor)
    {
        $this->authorize('update', $sensor);

        $data = $request->validate([
            'last_message' => ['nullable', 'date'],
            'created_by' => ['required', 'integer'],
            'romm_id' => ['required', 'integer'],
        ]);

        $sensor->update($data);

        return $sensor;
    }

    public function destroy(Sensor $sensor)
    {
        $this->authorize('delete', $sensor);

        $sensor->delete();

        return response()->json();
    }
}
