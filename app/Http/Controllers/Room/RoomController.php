<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Room::class);

        return Room::all();
    }

    public function store(Request $request)
    {
        //$this->authorize('create', Room::class);

        $data = $request->validate([
            'name' => ['string','unique:rooms'],
        ]);


        return Room::create($data);
    }

    public function show(Room $room)
    {
        $this->authorize('view', $room);

        return $room;
    }

    public function update(Request $request, Room $room)
    {
        $this->authorize('update', $room);

        $data = $request->validate([
            'name' => ['string', 'unique:rooms'],
        ]);

        $room->update($data);

        return $room;
    }

    public function destroy(Room $room)
    {
        $this->authorize('delete', $room);

        $room->delete();

        return response()->json();
    }
}
