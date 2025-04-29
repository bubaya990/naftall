<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Computer;
use App\Models\Printer;
use App\Models\Hotspot;
use App\Models\IpPhone;
use App\Models\Site;
use App\Models\Location;
use App\Models\Room;
use App\Models\Corridor;
use App\Models\Ram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaterialController extends Controller
{
    // Display dashboard
    public function index()
{
    // Count materials by type (global totals)
    $totalComputers = Material::where('materialable_type', Computer::class)->count();
    $totalPrinters = Material::where('materialable_type', Printer::class)->count();
    $totalIpPhones = Material::where('materialable_type', IpPhone::class)->count();
    $totalHotspots = Material::where('materialable_type', Hotspot::class)->count();

    $materialableType = Material::first()?->materialable_type;
    $type = $materialableType;
    $materials = Material::paginate(10);

    // Get all sites with their materials counts
    $siteMaterialCounts = Site::with(['locations.rooms.materials', 'locations.corridors.materials'])
        ->get()
        ->map(function($site) {
            // Initialize counters
            $counts = [
                'computers' => 0,
                'printers' => 0,
                'ipPhones' => 0,
                'hotspots' => 0
            ];

            // Track seen material IDs to prevent duplicates
            $seenMaterialIds = [];

            foreach ($site->locations as $location) {
                // Count materials in rooms
                foreach ($location->rooms as $room) {
                    foreach ($room->materials as $material) {
                        if (!in_array($material->id, $seenMaterialIds)) {
                            $seenMaterialIds[] = $material->id;
                            $this->incrementMaterialCount($counts, $material->materialable_type);
                        }
                    }
                }

                // Count materials in corridors
                foreach ($location->corridors as $corridor) {
                    foreach ($corridor->materials as $material) {
                        if (!in_array($material->id, $seenMaterialIds)) {
                            $seenMaterialIds[] = $material->id;
                            $this->incrementMaterialCount($counts, $material->materialable_type);
                        }
                    }
                }
            }

            return [
                'site_name' => $site->name,
                'computers' => $counts['computers'],
                'printers' => $counts['printers'],
                'ipPhones' => $counts['ipPhones'],
                'hotspots' => $counts['hotspots'],
            ];
        })
        ->values()
        ->toArray();

    return view('superadmin.materials.gestion-material', compact(
        'totalComputers',
        'totalPrinters',
        'totalIpPhones',
        'totalHotspots',
        'siteMaterialCounts',
        'type',
        'materials'
    ));
}

// Helper method to increment the correct counter
private function incrementMaterialCount(&$counts, $materialableType)
{
    switch ($materialableType) {
        case Computer::class:
            $counts['computers']++;
            break;
        case Printer::class:
            $counts['printers']++;
            break;
        case IpPhone::class:
            $counts['ipPhones']++;
            break;
        case Hotspot::class:
            $counts['hotspots']++;
            break;
    }
}
    // Store a new material
    public function store(Request $request, $type)
    {
        Log::info('Attempting to store new material', ['type' => $type, 'data' => $request->all()]);

        try {
            $validatedData = $request->validate($this->getValidationRules($type));
            Log::info('Validation passed', ['validated' => $validatedData]);

            DB::beginTransaction();

            // Create the specific material type first
            $materialable = $this->createMaterialable($type, $request);
            if (!$materialable) {
                throw new \Exception("Failed to create materialable record of type: {$type}");
            }

            // Then create the material record
            $materialData = [
                'inventory_number' => $validatedData['inventory_number'],
                'serial_number' => $validatedData['serial_number'],
                'state' => $validatedData['state'],
                'room_id' => $request->location_type === 'room' ? $validatedData['room_id'] : null,
                'corridor_id' => $request->location_type === 'corridor' ? $validatedData['corridor_id'] : null,
            ];

            $material = $materialable->material()->create($materialData);
            Log::info('Material created successfully', ['material_id' => $material->id]);

            DB::commit();

            return redirect()
                ->route('superadmin.materials.list', $type)
                ->with('success', 'Matériel ajouté avec succès!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Material creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'ajout du matériel: ' . $e->getMessage());
        }
    }

    private function createMaterialable($type, $request)
    {
        $modelClass = $this->getModelFromType($type);
        if (!$modelClass) {
            Log::error('Invalid material type requested', ['type' => $type]);
            return null;
        }

        $materialableData = [];
        switch ($type) {
            case 'computers':
                $materialableData = [
                    'computer_brand' => $request->computer_brand,
                    'computer_model' => $request->computer_model,
                    'OS' => $request->OS,
                    'ram_id' => $request->ram_id,
                ];
                break;
            case 'printers':
                $materialableData = [
                    'printer_brand' => $request->printer_brand,
                    'printer_model' => $request->printer_model,
                ];
                break;
            case 'ip-phones':
                $materialableData = [
                    'mac_number' => $request->mac_number,
                ];
                break;
            case 'hotspots':
                $materialableData = [
                    'password' => $request->password,
                ];
                break;
        }

        try {
            $materialable = $modelClass::create($materialableData);
            Log::info('Materialable created', ['type' => $type, 'id' => $materialable->id]);
            return $materialable;
        } catch (\Exception $e) {
            Log::error('Failed to create materialable', [
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    // List materials based on type
    public function list($type)
    {
        $modelClass = $this->getModelFromType($type);
        if (!$modelClass) {
            abort(404, "Type de matériel non trouvé");
        }

        $materials = Material::with([
                'materialable', 
                'room.location.site', 
                'corridor.location.site'
            ])
            ->where('materialable_type', $modelClass)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('superadmin.materials.list', compact('materials', 'type'));
    }

    // AJAX: Get locations by site
    public function getLocationsBySite($siteId)
    {
        $locations = Location::where('site_id', $siteId)->get();
        return response()->json($locations);
    }

    // AJAX: Get rooms by location
    public function getRoomsByLocation($locationId)
    {
        $rooms = Room::where('location_id', $locationId)->get();
        return response()->json($rooms);
    }

    // AJAX: Get corridors by location
    public function getCorridorsByLocation($locationId)
    {
        $corridors = Corridor::where('location_id', $locationId)->get();
        return response()->json($corridors);
    }

    // Helper method to map the material type to the model class
    private function getModelFromType($type)
    {
        $models = [
            'computers' => Computer::class,
            'printers' => Printer::class,
            'ip-phones' => IpPhone::class,
            'hotspots' => Hotspot::class,
        ];
        return $models[$type] ?? null;
    }

    // Show add material form
    public function create($type)
    {
        if (!$this->getModelFromType($type)) {
            abort(404, "Type de matériel non valide");
        }

        $sites = Site::all();
        $states = ['bon', 'défectueux', 'hors_service'];
        $rams = Ram::all();
        
        return view('superadmin.materials.add', compact('type', 'sites', 'states', 'rams'));
    }

    // Validation rules based on material type
    private function getValidationRules($type)
    {
        $commonRules = [
            'inventory_number' => 'required|unique:materials',
            'serial_number' => 'required|unique:materials',
            'state' => 'required|in:bon,défectueux,hors_service',
            'site_id' => 'required|exists:sites,id',
            'location_id' => 'required|exists:locations,id',
            'location_type' => 'required|in:room,corridor',
            'room_id' => 'required_if:location_type,room|nullable|exists:rooms,id',
            'corridor_id' => 'required_if:location_type,corridor|nullable|exists:corridors,id',
        ];
        
        $typeRules = [];
        
        switch ($type) {
            case 'computers':
                $typeRules = [
                    'computer_brand' => 'required',
                    'computer_model' => 'required',
                    'OS' => 'required|in:Windows7,Windows8,Windows10,Linux',
                    'ram_id' => 'required|exists:rams,id',
                ];
                break;
            case 'printers':
                $typeRules = [
                    'printer_brand' => 'required',
                    'printer_model' => 'required',
                ];
                break;
            case 'ip-phones':
                $typeRules = [
                    'mac_number' => 'required|unique:ip_phones',
                ];
                break;
            case 'hotspots':
                $typeRules = [
                    'password' => 'required|unique:hotspots',
                ];
                break;
        }
        
        return array_merge($commonRules, $typeRules);
    }

    // Destroy a material
    public function destroy($type, $id)
    {
        try {
            $material = Material::with('materialable')->findOrFail($id);
            Log::info('Deleting material', ['id' => $id, 'type' => $type]);

            DB::beginTransaction();

            // Delete the materialable record first
            if ($material->materialable) {
                $material->materialable->delete();
                Log::info('Deleted materialable', ['type' => $type, 'id' => $material->materialable->id]);
            }

            // Then delete the material
            $material->delete();
            Log::info('Deleted material record', ['id' => $id]);

            DB::commit();

            return redirect()
                ->route('superadmin.materials.list', $type)
                ->with('success', 'Matériel supprimé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Deletion failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Échec de la suppression: ' . $e->getMessage());
        }
    }
    
}