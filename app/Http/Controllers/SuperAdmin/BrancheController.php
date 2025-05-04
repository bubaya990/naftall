<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branche;
use App\Models\Site;
use Illuminate\Support\Facades\Log;

class BrancheController extends Controller
{
    public function carburantSites()
    {
        // Get unique sites that have carburant branches
        $carburantBranches = Branche::with('site')
            ->where('name', 'carburant')
            ->get()
            ->unique('site_id')
            ->values(); // Réindexer le tableau après unique()

        return view('superadmin.cbr', compact('carburantBranches'));
    }

// SuperAdmin/BrancheController.php
public function commercialDetails()
{
    $stations = Site::whereHas('branche', function($q) {
        $q->where('name', 'commercial');
    })
    ->with('children') // Load children directly
    ->whereNull('parent_id') // Only GD (top level) stations, no child stations alone
    ->get();

    return view('superadmin.com', compact('stations'));
}
public function commercialStructure()
{
    try {
        // Charger toutes les branches commerciales avec leurs relations
        $commercialBranches = Branche::with(['children' => function($query) {
            $query->with(['children' => function($query) {
                $query->with('children');
            }]);
        }])
        ->where('name', 'Commercial')
        ->get();

        $site = Site::first();

        if ($commercialBranches->isEmpty()) {
            return view('superadmin.com', [
                'commercialBranches' => null,
                'site' => $site
            ]);
        }

        // Debug pour vérifier les branches chargées
        foreach ($commercialBranches as $branche) {
            Log::info('Commercial Branche Children for site ' . $branche->site->name . ':', [
                'children' => $branche->children->pluck('name')->toArray()
            ]);
        }

        return view('superadmin.com', [
            'commercialBranches' => $commercialBranches,
            'site' => $site
        ]);
    } catch (\Exception $e) {
        Log::error('Error in commercialStructure: ' . $e->getMessage());
        return view('superadmin.com', [
            'commercialBranches' => null,
            'site' => null
        ]);
    }
}

public function showBranche($site, $brancheType)
{
    try {
        $site = Site::findOrFail($site);
        $branche = Branche::where('site_id', $site->id)
            ->where('name', $brancheType)
            ->with('children')
            ->firstOrFail();

        return view('superadmin.sites', [
            'site' => $site,
            'branche' => $branche,
            'brancheType' => $brancheType,
            'children' => $branche->children
        ]);
    } catch (\Exception $e) {
        Log::error('Error in showBranche: ' . $e->getMessage());
        return redirect()->route('superadmin.com')->with('error', 'Branche non trouvée');
    }
}

public function showBrancheDetail($site, $brancheType, $branche)
{
    try {
        $site = Site::findOrFail($site);
        $branche = Branche::with('children')
            ->findOrFail($branche);

        return view('superadmin.sites', [
            'site' => $site,
            'branche' => $branche,
            'brancheType' => $brancheType,
            'children' => $branche->children
        ]);
    } catch (\Exception $e) {
        Log::error('Error in showBrancheDetail: ' . $e->getMessage());
        return redirect()->route('superadmin.com')->with('error', 'Branche non trouvée');
    }
}

}