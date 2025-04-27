<?php


namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Corridor;
use App\Models\Hotspot;
use App\Models\Material;
use Illuminate\Http\Request;

class HotSpotController extends Controller
{
    // Show the form to create a new Hotspot material
    public function create()
    {
        $rooms = Room::all(); // Fetch all rooms
        $corridors = Corridor::all(); // Fetch all corridors

        return view('superadmin.materials.hotspotcreate', compact('rooms', 'corridors'));
    }

    // Store a new Hotspot material
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'password' => 'required|string',
            'state' => 'required|string',
            'room_id' => 'required|exists:rooms,id',
            'corridor_id' => 'required|exists:corridors,id',
        ]);

        // Create a new Hotspot material
        $hotspot = new Hotspot();
        $hotspot->password = $request->password;
        $hotspot->state = $request->state;
        $hotspot->room_id = $request->room_id;
        $hotspot->corridor_id = $request->corridor_id;
        $hotspot->save();

        // Create the Material and associate it with the Hotspot
        $material = new Material();
        $material->materialable_type = Hotspot::class;
        $material->materialable_id = $hotspot->id;
        $material->save();

        // Redirect with a success message
        return redirect()->route('superadmin.materials.hotspotcreate')
                         ->with('success', 'Hotspot material created successfully!');
    }
}
