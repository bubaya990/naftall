<?php

namespace App\Http\Controllers\SuperAdmin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\Room;
use App\Models\Floor;



class LocationController extends Controller
{
   
 
public function gestionLocalite()
{
    if (Auth::user()->role !== 'superadmin') {
        abort(403, 'Unauthorized action.');
    }

    $sites = \App\Models\Site::with(['locations.floor'])->get();

    return view('superadmin.locations.gestion-localite', compact('sites'));
}
public function editType($id)
{
    $location = Location::findOrFail($id);
    // Or whatever logic you want to edit the type of a location

    return view('superadmin.locations.edit-type', compact('location'));

}
public function destroy(Location $location)
{
    if (Auth::user()->role !== 'superadmin') {
        abort(403, 'Unauthorized action.');
    }

    // Delete related data
    $location->corridors()->delete();
    $location->rooms()->delete();
    
    $location->delete();

    return redirect()->route('superadmin.locations.gestion-localite')
        ->with('success', 'Location deleted successfully!');
}

public function create()
{
    $sites = Site::all();  // Removed fully qualified name
    $types = Location::getTypes();
    
    return view('superadmin.locations.create', compact('sites', 'types'));
}

public function store(Request $request)
{
    $request->validate([
        'site_id' => 'required|exists:sites,id',
        'type' => 'required|string|in:'.implode(',', Location::getTypes()),
        'floor_number' => 'required_if:type,Étage|nullable|integer|min:0',
    ]);

    

    $floor = null;
    
    if ($request->type === 'Étage') {
        // Handle floor creation without site reference
        $floorNumber = $request->floor_number;
        
        // If no floor number provided, get next available
        if (!$floorNumber) {
            $floorNumber = Floor::max('floor_number') + 1 ?? 1;
        }
        
        // Find or create floor (independent of site)
        $floor = Floor::firstOrCreate(
            ['floor_number' => $floorNumber],
            ['name' => 'Floor ' . $floorNumber]
        );
    }

    // Create location with site_id and optional floor_id
    Location::create([
        'site_id' => $request->site_id,
        'type' => $request->type,
        'floor_id' => $floor?->id, // Null if not 'Étage'
    ]);

    return redirect()->route('superadmin.locations.gestion-localite')
        ->with('success', 'Localité créée avec succès!');
}

public function showStore()
{
    return redirect()->route('superadmin.locations.create');
}

public function updateType(Request $request, $id)
{
    $request->validate([
        'type' => 'required|string|max:255',
    ]);

    $location = Location::findOrFail($id);
    $location->type = $request->input('type');
    $location->save();

    return redirect()->route('locations.index')->with('success', 'Location type updated successfully.');
}
 // Room Management Methods

 public function rooms($location)
 {
     $rooms = \App\Models\Room::where('location_id', $location)->with('location')->get();
 
     return view('superadmin.locations.rooms', [
         'rooms' => $rooms,
         'locationId' => $location // ✅ this is what you need for the view
     ]);
 }
 
 

 public function addroom(Location $location)
    {
        return view('superadmin.locations.addroom', compact('location'));
    }

    public function storeRoom(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:rooms,code',
            'type' => 'required|string|in:Office,Storage,Meeting',
        ], [
            'code.unique' => 'This room code already exists.',
            'type.in' => 'Please select a valid room type.',
        ]);

        try {
            Room::create([
                'name' => $request->name,
                'code' => $request->code,
                'type' => $request->type,
                'location_id' => $location->id
            ]);

            return redirect()->route('superadmin.locations.rooms', $location)
                   ->with('success', 'Room added successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating room: ' . $e->getMessage());
        }
    }

    

 public function updateRoomType(Request $request, Location $location, Room $room)
 {
     $request->validate([
         'type' => 'required|string|in:Office,Storage,Meeting',
     ]);

     $room->update(['type' => $request->type]);
     
     return back()->with('success', 'Room type updated.');
 }

 public function destroyRoom(Location $location, Room $room)
 {
     $room->delete();
     return back()->with('success', 'Room deleted successfully.');
 }
}

