<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Branche;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BrancheController extends Controller
{
    /**
     * Show carburant sites (CBR)
     */
    public function carburantSites()
    {
        $carburantBranches = Branche::with('site')
            ->where('name', 'carburant')
            ->get()
            ->unique('site_id')
            ->values();

        return view('superadmin.cbr', compact('carburantBranches'));
    }

    /**
     * Show commercial structure (COM)
     */
    public function commercialStructure()
    {
        try {
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

            Log::info('Commercial Branches Loaded', [
                'count' => $commercialBranches->count(),
                'sites' => $commercialBranches->pluck('site.name')
            ]);

            return view('superadmin.com', [
                'commercialBranches' => $commercialBranches,
                'site' => $site
            ]);
        } catch (\Exception $e) {
            Log::error('Commercial Structure Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load commercial structure');
        }
    }

    /**
     * Show branch details
     */
    public function showBranche(Site $site, $brancheType)
    {
        try {
            $branche = Branche::where('site_id', $site->id)
                ->where('name', $brancheType)
                ->with('children')
                ->firstOrFail();

            return view('superadmin.sites', [
                'site' => $site,
                'branche' => $branche,
                'brancheType' => $brancheType,
                'showFloorPlans' => $brancheType === 'carburant' && $site->name === 'Siege',
                'showAgencePlan' => $brancheType === 'agence' && $site->name === 'Siege'
            ]);
        } catch (\Exception $e) {
            Log::error('Show Branche Error: ' . $e->getMessage());
            return redirect()->route('superadmin.com')->with('error', 'Branch not found');
        }
    }

    /**
     * Show branch sub-details
     */
    public function showBrancheDetail(Site $site, $brancheType, Branche $branche)
    {
        try {
            return view('superadmin.sites', [
                'site' => $site,
                'branche' => $branche->load('children'),
                'brancheType' => $brancheType,
                'showFloorPlans' => false,
                'showAgencePlan' => false
            ]);
        } catch (\Exception $e) {
            Log::error('Show Branche Detail Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load branch details');
        }
    }
}