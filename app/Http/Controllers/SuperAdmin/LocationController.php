<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\Room;
use App\Models\Floor;
use App\Models\Ram;
use App\Models\Corridor;
use App\Models\Material;
use App\Models\Hotspot;
use App\Models\Computer;
use App\Models\Printer;
use App\Models\IpPhone;

class LocationController extends Controller
{

//display location page
public function gestionLocalite()
{
      $user = auth()->user();
      if ($user->role === 'utilisateur') {
        abort(403, 'Unauthorized access.');
    }

    $sites = \App\Models\Site::with(['locations.floor'])->get();

    return view('superadmin.locations.gestion-localite', compact('sites'));
}


public function editType($id)
{

    if (auth()->user()->role !== 'superadmin')
    {
        abort(403, 'Unauthorized access.');
    }

    $location = Location::findOrFail($id);
   

    return view('superadmin.locations.edit-type', compact('location'));

}

//delet location
public function destroy(Location $location)
{

    if (auth()->user()->role !== 'superadmin') {
    abort(403, 'Unauthorized access.');
}

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
//creat display
public function create()
{
    if (auth()->user()->role !== 'superadmin') {
    abort(403, 'Unauthorized access.');
}

    $sites = Site::all();  
    $types = Location::getTypes();

    return view('superadmin.locations.create', compact('sites', 'types'));
}
//creat and stor
public function store(Request $request)
{
    if (auth()->user()->role !== 'superadmin') {
    abort(403, 'Unauthorized access.');
}

    $request->validate([
        'site_id' => 'required|exists:sites,id',
        'type' => 'required|string|in:'.implode(',', Location::getTypes()),
        'floor_number' => 'required_if:type,Étage|nullable|integer|min:0',
    ]);



    $floor = null;
    $locationName = '';

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
        'floor_id' => $floor?->id,
        'name' => $request->name, // Use the name from the form
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
    if (auth()->user()->role !== 'superadmin') {
    abort(403, 'Unauthorized access.');
}

    $request->validate([
        'type' => 'required|string|max:255',
    ]);

    $location = Location::findOrFail($id);
    $location->type = $request->input('type');
    $location->save();

    return redirect()->route('locations.index')->with('success', 'Location type updated successfully.');
}
 
//miss auth
 public function rooms($location)
 {
    
     $rooms = \App\Models\Room::where('location_id', $location)->with('location')->get();

     return view('superadmin.locations.rooms', [
         'rooms' => $rooms,
         'locationId' => $location 
     ]);
 }



 public function addroom(Location $location)
    {
        if (auth()->user()->role !== 'superadmin') {
    abort(403, 'Unauthorized access.');
}

        return view('superadmin.locations.addroom', compact('location'));
    }

    public function storeRoom(Request $request, Location $location)
{
    if (auth()->user()->role !== 'superadmin') {
    abort(403, 'Unauthorized access.');
}

    // Debug: show all input + location ID
    \Log::debug('Room creation attempt', [
        'input' => $request->all(),
        'location_id' => $location->id
    ]);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:rooms,code',
        'type' => 'required|string|in:Bureau,Salle reunion,Salle reseau',
    ]);

    try {
        $room = new Room();
        $room->name = $validated['name'];
        $room->code = $validated['code'];
        $room->type = $validated['type'];
        $room->location_id = $location->id;

        if ($room->save()) {
            \Log::debug('Room saved successfully', $room->toArray());
            return redirect()
                ->route('superadmin.locations.rooms', $location)
                ->with('success', 'Room created!');
        } else {
            \Log::error('Room failed to save silently');
            return back()->with('error', 'Room failed to save (no error thrown)');
        }

    } catch (\Exception $e) {
        \Log::error('Room save error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()
            ->withInput()
            ->with('error', 'Error: ' . $e->getMessage());
    }
}




 public function destroyRoom(Location $location, Room $room)
 {
    if (auth()->user()->role !== 'superadmin') {
    abort(403, 'Unauthorized access.');
}

     $room->delete();
     return back()->with('success', 'Room deleted successfully.');
 }


 //miss auth
 public function corridors($locationId)
 {
     $location = Location::with(['site', 'corridors'])->findOrFail($locationId);

     return view('superadmin.locations.corridors', [
         'location' => $location,
         'corridors' => $location->corridors
     ]);
 }

 public function addcorridor(Location $location)
 {
    if (auth()->user()->role !== 'superadmin') {
    abort(403, 'Unauthorized access.');
}

     return view('superadmin.locations.addcorridor', compact('location'));
 }

 public function storeCorridor(Request $request, Location $location)
 {
    if (auth()->user()->role !== 'superadmin') {
    abort(403, 'Unauthorized access.');
}

     $request->validate([
         'name' => 'nullable|string|max:255',
     ]);

     $corridor = new Corridor();
     $corridor->location_id = $location->id;
     $corridor->name = $request->name;
     $corridor->save();

     return redirect()->route('superadmin.locations.corridors', $location->id)
         ->with('success', 'Couloir ajouté avec succès!');
 }

 public function destroyCorridor(Location $location, Corridor $corridor)
 {
    if (auth()->user()->role !== 'superadmin') {
    abort(403, 'Unauthorized access.');
}

     $corridor->delete();

     return back()->with('success', 'Couloir supprimé avec succès!');
 }

 public function updateRoomType(Request $request, Location $location, Room $room)
 {
    if (auth()->user()->role !== 'superadmin') {
    abort(403, 'Unauthorized access.');
}

     $request->validate([
         'type' => 'required|string|in:Bureau,Salle reunion,Salle reseau',
     ]);

     $room->update(['type' => $request->type]);

     return response()->json(['success' => true]);
 }


 //location view dispaly
public function viewRoomMaterials(Location $location, Room $room)
{
    $user = auth()->user();

    if ($room->location_id !== $location->id) {
        abort(404, 'Room does not belong to the specified location.');
    }

   
    if ($user->role !== 'superadmin') {
        if ($user->role === 'admin') {
            if ($user->site_id !== $location->site_id) {
                abort(403, 'Unauthorized access.');
            }
        } else {
            abort(403, 'Unauthorized access.');
        }
    }

    $materials = $room->materials()->with('materialable')->get();

    return view('superadmin.locations.view', [
        'location' => $location,
        'entity' => $room,
        'materials' => $materials,
        'entityType' => 'room',
    ]);
}

//corridor list
public function viewCorridorMaterials(Location $location, Corridor $corridor)
{

     $user = auth()->user();

    // Superadmin always has access
    if ($user->role !== 'superadmin') {
        // Check if user is admin and has access to this site's ID
        if ($user->role === 'admin') {
            // Get site ID of the location
            $siteId = $location->site_id;

            // Compare with the user's allowed site_id
            if ($user->site_id !== $siteId) {
                abort(403, 'Unauthorized access.');
            }
        } else {
            // Not superadmin or admin — deny access
            abort(403, 'Unauthorized access.');
        }
    }
    $materials = $corridor->materials()->with('materialable')->get();

    return view('superadmin.locations.view', [
        'location' => $location,
        'entity' => $corridor,
        'materials' => $materials,
        'entityType' => 'corridor'
    ]);
}


//addM display

public function addMaterial(Request $request, $locationId, $entityType, $entityId, $type = 'computers')
{
     $user = auth()->user();
$location = Location::findOrFail($locationId);
    // Superadmin always has access
    if ($user->role !== 'superadmin') {
        // Check if user is admin and has access to this site's ID
        if ($user->role === 'admin') {
            // Get site ID of the location
            $siteId = $location->site_id;

            // Compare with the user's allowed site_id
            if ($user->site_id !== $siteId) {
                abort(403, 'Unauthorized access.');
            }
        } else {
            // Not superadmin or admin — deny access
            abort(403, 'Unauthorized access.');
        }
    }
    $location = Location::findOrFail($locationId);

    if ($entityType === 'room') {
        $entity = Room::findOrFail($entityId);
    } else {
        $entity = Corridor::findOrFail($entityId);
    }

    // Validate the type parameter
    $validTypes = ['computers', 'printers', 'ip-phones', 'hotspots'];
    if (!in_array($type, $validTypes)) {
        $type = 'computers'; // default to computers if invalid type
    }

    $states = ['bon', 'défectueux', 'hors_service'];
    $rams = \App\Models\Ram::all();

    return view('superadmin.locations.addM', [
        'location' => $location,
        'entity' => $entity,
        'entityType' => $entityType,
        'type' => $type,
        'states' => $states,
        'rams' => $rams
    ]);
}

//add and stor material
public function storeMaterial(Request $request, $locationId, $entityType, $entityId)
{
     $user = auth()->user();

    // Superadmin always has access
    if ($user->role !== 'superadmin') {
        // Check if user is admin and has access to this site's ID
        if ($user->role === 'admin') {
            // Get site ID of the location
            $siteId = $location->site_id;

            // Compare with the user's allowed site_id
            if ($user->site_id !== $siteId) {
                abort(403, 'Unauthorized access.');
            }
        } else {
            // Not superadmin or admin — deny access
            abort(403, 'Unauthorized access.');
        }
    }
    // Find the location and entity (room/corridor)
    $location = Location::findOrFail($locationId);
    $entity = $entityType === 'room' 
        ? Room::findOrFail($entityId)
        : Corridor::findOrFail($entityId);

    // Common validation for all material types
    $validated = $request->validate([
        'inventory_number' => 'required|string|max:255|unique:materials,inventory_number',
        'serial_number' => 'nullable|string|max:255|unique:materials,serial_number',
        'state' => 'required|string|in:bon,défectueux,hors_service',
        'type' => 'required|string|in:computers,printers,ip-phones,hotspots',
    ]);

    // Type-specific validation
    $typeRules = [
        'computers' => [
            'computer_brand' => 'required|string|max:255',
            'computer_model' => 'required|string|max:255',
            'OS' => 'required|string|in:Windows7,Windows8,Windows10,Linux',
            'ram_id' => 'required|exists:rams,id',
        ],
        'printers' => [
            'printer_brand' => 'required|string|max:255',
            'printer_model' => 'required|string|max:255',
        ],
        'ip-phones' => [
            'mac_number' => 'required|string|max:255',
        ],
        'hotspots' => [
            'password' => 'required|string|max:255',
        ],
    ];

    $request->validate($typeRules[$validated['type']]);

    try {
        DB::beginTransaction();

        // Create the specific material type
        $materialable = null;
        switch ($validated['type']) {
            case 'computers':
                $materialable = Computer::create([
                    'computer_brand' => $request->computer_brand,
                    'computer_model' => $request->computer_model,
                    'OS' => $request->OS,
                    'ram_id' => $request->ram_id,
                ]);
                break;

            case 'printers':
                $materialable = Printer::create([
                    'printer_brand' => $request->printer_brand,
                    'printer_model' => $request->printer_model,
                ]);
                break;

            case 'ip-phones':
                $materialable = IpPhone::create([
                    'mac_number' => $request->mac_number,
                ]);
                break;

            case 'hotspots':
                $materialable = Hotspot::create([
                    'password' => $request->password,
                ]);
                break;
        }

        // Create the material record
        $materialData = [
            'inventory_number' => $validated['inventory_number'],
            'serial_number' => $validated['serial_number'],
            'state' => $validated['state'],
            'name' => 'Matériel', // Default name as per your form
        ];

        // Set the appropriate relationship
        if ($entityType === 'room') {
            $materialData['room_id'] = $entity->id;
        } else {
            $materialData['corridor_id'] = $entity->id;
        }

        // Create and associate the material
        $material = new Material($materialData);
        
        if ($materialable) {
            $materialable->material()->save($material);
        } else {
            $material->save();
        }

        DB::commit();

        return redirect()
            ->route('superadmin.locations.view', [
                'location' => $location->id,
                'entityType' => $entityType,
                'entity' => $entity->id
            ])
            ->with('success', 'Matériel ajouté avec succès!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()
            ->withInput()
            ->with('error', 'Erreur: ' . $e->getMessage());
    }
}


public function viewEntityMaterials(Location $location, $entityType, $entityId)
{
     $user = auth()->user();
     
    // Superadmin always has access
    if ($user->role !== 'superadmin') {
        // Check if user is admin and has access to this site's ID
        if ($user->role === 'admin') {
            // Get site ID of the location
            $siteId = $location->site_id;

            // Compare with the user's allowed site_id
            if ($user->site_id !== $siteId) {
                abort(403, 'Unauthorized access.');
            }
        } else {
            // Not superadmin or admin — deny access
            abort(403, 'Unauthorized access.');
        }
    }
    $entity = $entityType === 'room'
        ? Room::findOrFail($entityId)
        : Corridor::findOrFail($entityId);

    $materials = $entity->materials()->with('materialable')->get();
  $materials->each(function($material) {
        $material->type = strtolower(class_basename($material->materialable_type));
    });

    return view('superadmin.locations.view', [
        'location' => $location,
        'entity' => $entity,
        'materials' => $materials,
        'entityType' => $entityType
    ]);
}

//get room jason for plan
 public function getRoomsJson(Location $location)
{
    return response()->json(
        $location->rooms()->select('id', 'name', 'code')->get()
    );
}
//get corridor jason for plan
public function getCorridorsJson(Location $location)
{
    return response()->json(
        $location->corridors()->select('id', 'name')->get()
    );
}


public function show(Location $location)
{
    $user = auth()->user();
    
    // Authorization check
    if ($user->role !== 'superadmin') {
        if ($user->role === 'admin' && $user->site_id !== $location->site_id) {
            abort(403, 'Unauthorized access.');
        } elseif ($user->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
    }

    // Check if entityType and entity parameters are present
    $entityType = request()->input('entityType', 'room');
    $entityId = request()->input('entity');

    if ($entityType === 'room') {
        $room = Room::findOrFail($entityId);
        return $this->viewRoomMaterials($location, $room);
    } else {
        $corridor = Corridor::findOrFail($entityId);
        return $this->viewCorridorMaterials($location, $corridor);
    }
}

}