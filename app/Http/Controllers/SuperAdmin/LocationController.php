<?php

namespace App\Http\Controllers\SuperAdmin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\Room;
use App\Models\Floor;
use App\Models\Ram;
use App\Models\Corridor;
use App\Models\Material;


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
     $room->delete();
     return back()->with('success', 'Room deleted successfully.');
 }
 // Add these methods to your LocationController
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
     return view('superadmin.locations.addcorridor', compact('location'));
 }

 public function storeCorridor(Request $request, Location $location)
 {
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
     $corridor->delete();

     return back()->with('success', 'Couloir supprimé avec succès!');
 }

 public function updateRoomType(Request $request, Location $location, Room $room)
 {
     $request->validate([
         'type' => 'required|string|in:Bureau,Salle reunion,Salle reseau',
     ]);

     $room->update(['type' => $request->type]);

     return response()->json(['success' => true]);
 }

 public function viewRoomMaterials(Location $location, Room $room)
{
    $materials = $room->materials()->with('materialable')->get();

    return view('superadmin.locations.view', [
        'location' => $location,
        'entity' => $room,
        'materials' => $materials,
        'entityType' => 'room'
    ]);
}

public function viewCorridorMaterials(Location $location, Corridor $corridor)
{
    $materials = $corridor->materials()->with('materialable')->get();

    return view('superadmin.locations.view', [
        'location' => $location,
        'entity' => $corridor,
        'materials' => $materials,
        'entityType' => 'corridor'
    ]);
}




public function addMaterial(Request $request, $locationId, $entityType, $entityId, $type = 'computers')
{
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

public function storeMaterial(Request $request, $locationId, $entityType, $entityId)
{
    $location = Location::findOrFail($locationId);

    if ($entityType === 'room') {
        $entity = Room::findOrFail($entityId);
    } else {
        $entity = Corridor::findOrFail($entityId);
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'serial_number' => 'required|string|max:255|unique:materials,serial_number',
        'state' => 'required|string|in:bon,défectueux,hors_service',
        'type' => 'required|string|in:computers,printers,ip-phones,hotspots',
        'ram_id' => 'required_if:type,computers|nullable|exists:rams,id',
        'ip_address' => 'nullable|ip',
        'mac_address' => 'nullable|string|max:255',
        'model' => 'nullable|string|max:255',
        'manufacturer' => 'nullable|string|max:255',
        'printer_brand' => 'required_if:type,printers|nullable|string|max:255',
        'printer_model' => 'required_if:type,printers|nullable|string|max:255',
    ]);

    try {
        // Create the specific material type first
        $materialable = null;
        if ($validated['type'] === 'printers') {
            $materialable = \App\Models\Printer::create([
                'printer_brand' => $validated['printer_brand'],
                'printer_model' => $validated['printer_model'],
            ]);
        }

        // Create the material with the polymorphic relationship
        $material = new Material([
            'name' => $validated['name'],
            'serial_number' => $validated['serial_number'],
            'state' => $validated['state'],
            'type' => $validated['type'],
        ]);

        // Set the entity relationship
        if ($entityType === 'room') {
            $material->room_id = $entity->id;
        } else {
            $material->corridor_id = $entity->id;
        }

        // Save the material with the polymorphic relationship
        if ($materialable) {
            $materialable->material()->save($material);
        } else {
            $material->save();
        }

        if ($material) {
            return redirect()
                ->route('superadmin.locations.view', [
                    'location' => $location->id,
                    'entityType' => $entityType,
                    'entity' => $entity->id
                ])
                ->with('success', 'Matériel ajouté avec succès!');
        } else {
            return back()->with('error', 'Erreur lors de l\'ajout du matériel');
        }
    } catch (\Exception $e) {
        return back()
            ->withInput()
            ->with('error', 'Erreur: ' . $e->getMessage());
    }
}




}