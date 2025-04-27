<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\Printer;
use App\Models\Hotspot;
use App\Models\Computer;
use App\Models\IpPhone;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    protected array $validTypes = ['computer', 'printer', 'hotspot', 'ip-phone'];

    protected array $typeLabels = [
        'computer' => 'Ordinateurs',
        'printer' => 'Imprimantes',
        'hotspot' => 'Hotspots',
        'ip-phone' => 'Téléphones IP',
    ];

    public function index()
    {
        // Count all materials by type using polymorphic relationships
        $totalComputers = Material::where('materialable_type', Computer::class)->count();
        $totalPrinters = Material::where('materialable_type', Printer::class)->count();
        $totalHotspots = Material::where('materialable_type', Hotspot::class)->count();
        $totalIpPhones = Material::where('materialable_type', IpPhone::class)->count();
    
        // Get counts per site
        $sites = Site::with(['locations.rooms.materials', 'locations.corridors.materials'])
            ->get()
            ->map(function ($site) {
                // Initialize counters
                $counts = [
                    'computers' => 0,
                    'printers' => 0,
                    'hotspots' => 0,
                    'ip_phones' => 0,
                ];
    
                // Count materials in rooms
                foreach ($site->locations as $location) {
                    foreach ($location->rooms as $room) {
                        foreach ($room->materials as $material) {
                            switch ($material->materialable_type) {
                                case Computer::class:
                                    $counts['computers']++;
                                    break;
                                case Printer::class:
                                    $counts['printers']++;
                                    break;
                                case Hotspot::class:
                                    $counts['hotspots']++;
                                    break;
                                case IpPhone::class:
                                    $counts['ip_phones']++;
                                    break;
                            }
                        }
                    }
                    
                    // Count materials in corridors
                    foreach ($location->corridors as $corridor) {
                        foreach ($corridor->materials as $material) {
                            switch ($material->materialable_type) {
                                case Computer::class:
                                    $counts['computers']++;
                                    break;
                                case Printer::class:
                                    $counts['printers']++;
                                    break;
                                case Hotspot::class:
                                    $counts['hotspots']++;
                                    break;
                                case IpPhone::class:
                                    $counts['ip_phones']++;
                                    break;
                            }
                        }
                    }
                }
    
                return [
                    'id' => $site->id,
                    'name' => $site->name,
                    'computers_count' => $counts['computers'],
                    'printers_count' => $counts['printers'],
                    'hotspots_count' => $counts['hotspots'],
                    'ip_phones_count' => $counts['ip_phones'],
                ];
            });
    
        return view('superadmin.materials.gestion-material', compact(
            'totalComputers',
            'totalPrinters',
            'totalHotspots',
            'totalIpPhones',
            'sites'
        ));
    }
    public function computers()
{
    $computers = Material::with(['materialable', 'room.location.site', 'corridor.location.site'])
        ->where('materialable_type', Computer::class)
        ->paginate(15);

    return view('superadmin.materials.computers', compact('computers'));
}

public function printers()
{
    $printers = Material::with('materialable') // ✅ use 'materialable' here
        ->where('materialable_type', Printer::class)
        ->paginate(15);

    return view('superadmin.materials.printers', compact('printers'));
}

public function hotspots()
{
    $hotspots = Material::with('materialable', 'room.location.site', 'corridor.location.site')
        ->where('materialable_type', Hotspot::class)
        ->paginate(15);

    return view('superadmin.materials.hotspots', compact('hotspots'));
}


public function ipPhones()
{
    $ipphones = Material::with(['materialable', 'room.location.site', 'corridor.location.site'])
        ->where('materialable_type', 'App\Models\IpPhone')
        ->paginate(10);

    return view('superadmin.materials.ip-phones', compact('ipphones'));
}


    public function siteMaterials(Site $site, $type)
    {
        if (!in_array($type, $this->validTypes)) {
            abort(404);
        }

        $relationship = $type === 'ip-phone' ? 'ipPhone' : $type;

        $materials = Material::with(['room.location.site', 'corridor.location.site', $relationship])
            ->where('materialable_type', $type)
            ->where(function ($query) use ($site) {
                $query->whereHas('room.location', fn($q) => $q->where('site_id', $site->id))
                      ->orWhereHas('corridor.location', fn($q) => $q->where('site_id', $site->id));
            })
            ->paginate(15);

        $title = $this->typeLabels[$type] ?? 'Matériels';

        return view('superadmin.materials.site-materials', compact('site', 'type', 'materials', 'title'));
    }

    public function create($type)
    {
        if (!in_array($type, $this->validTypes)) {
            abort(404);
        }

        $sites = Site::with('locations.rooms', 'locations.corridors')->get();

        $rooms = $sites->pluck('locations')->flatten()->pluck('rooms')->flatten();
        return view('superadmin.materials.createcomputer', compact('rooms'));
            }

    public function store(Request $request, $type)
    {
        if (!in_array($type, $this->validTypes)) {
            abort(404);
        }

        $validated = $request->validate([
            'serial_number' => 'required|unique:materials',
            'model' => 'required',
            'site_id' => 'required|exists:sites,id',
            'location_type' => 'required|in:room,corridor',
            'location_id' => 'required',
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        $material = Material::create([
            'materialable_type' => $type,
            'serial_number' => $validated['serial_number'],
            'model' => $validated['model'],
            'status' => $validated['status'],
            'room_id' => $validated['location_type'] === 'room' ? $validated['location_id'] : null,
            'corridor_id' => $validated['location_type'] === 'corridor' ? $validated['location_id'] : null,
        ]);

        $relationship = $type === 'ip-phone' ? 'ipPhone' : $type;
        $specificData = $request->only(['ip_address', 'mac_address', 'additional_attributes']);
        $material->{$relationship}()->create($specificData);

        return redirect()->route('superadmin.materials.' . $this->pluralType($type))
            ->with('success', 'Matériel ajouté avec succès');
    }

    public function show($type, Material $material)
    {
        if ($material->materialable_type !== $type || !in_array($type, $this->validTypes)) {
            abort(404);
        }

        $relationship = $type === 'ip-phone' ? 'ipPhone' : $type;
        $material->load(['room.location.site', 'corridor.location.site', $relationship]);

        return view('superadmin.materials.show', compact('material', 'type'));
    }

    public function edit($type, Material $material)
    {
        if ($material->materialable_type !== $type || !in_array($type, $this->validTypes)) {
            abort(404);
        }

        $sites = Site::with('locations.rooms', 'locations.corridors')->get();
        $relationship = $type === 'ip-phone' ? 'ipPhone' : $type;
        $material->load([$relationship]);

        return view('superadmin.materials.edit', compact('material', 'type', 'sites'));
    }

    public function update(Request $request, $type, Material $material)
    {
        if ($material->materialable_type !== $type || !in_array($type, $this->validTypes)) {
            abort(404);
        }

        $validated = $request->validate([
            'serial_number' => 'required|unique:materials,serial_number,' . $material->id,
            'model' => 'required',
            'site_id' => 'required|exists:sites,id',
            'location_type' => 'required|in:room,corridor',
            'location_id' => 'required',
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        $material->update([
            'serial_number' => $validated['serial_number'],
            'model' => $validated['model'],
            'status' => $validated['status'],
            'room_id' => $validated['location_type'] === 'room' ? $validated['location_id'] : null,
            'corridor_id' => $validated['location_type'] === 'corridor' ? $validated['location_id'] : null,
        ]);

        $relationship = $type === 'ip-phone' ? 'ipPhone' : $type;
        $specificData = $request->only(['ip_address', 'mac_address', 'additional_attributes']);
        $material->{$relationship}()->update($specificData);

        return redirect()->route('superadmin.materials.' . $this->pluralType($type))
            ->with('success', 'Matériel mis à jour avec succès');
    }

    public function destroy($type, Material $material)
    {
        if ($material->materialable_type !== $type || !in_array($type, $this->validTypes)) {
            abort(404);
        }

        $relationship = $type === 'ip-phone' ? 'ipPhone' : $type;

        $material->{$relationship}()->delete();
        $material->delete();

        return redirect()->route('superadmin.materials.' . $this->pluralType($type))
            ->with('success', 'Matériel supprimé avec succès');
    }

    protected function pluralType($type)
    {
        return match ($type) {
            'computer' => 'computers',
            'printer' => 'printers',
            'hotspot' => 'hotspots',
            'ip-phone' => 'ip-phones',
            default => $type . 's',
        };
    }

    
}
