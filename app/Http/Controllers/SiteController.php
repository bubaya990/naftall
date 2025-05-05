<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Branche;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display main site view
     */
    public function show(Site $site, $brancheType)
    {
        $branche = Branche::where('site_id', $site->id)
                          ->where('name', $brancheType)
                          ->first(); // Removed `whereNull('parent_id')`
    
        if (!$branche) {
            abort(404, "Branche '{$brancheType}' not found for this site.");
        }
    
        return view('superadmin.sites', compact('site', 'branche'));
    }
    

    /**
     * Show carburant branch
     */
    public function showCarburant(Site $site)
    {
        return $this->show($site, 'carburant');
    }

    /**
     * Show commercial branch
     */
    public function showCommercial(Site $site)
    {
        return $this->show($site, 'commercial');
    }

    /**
     * Show agence branch
     */
    public function showAgence(Site $site)
    {
        return $this->show($site, 'agence');
    }

    /**
     * Get branch data (AJAX)
     */
    public function getBrancheData(Request $request)
    {
        $request->validate([
            'site' => 'required|exists:sites,id',
            'brancheType' => 'required|string'
        ]);

        $branche = Branche::where('site_id', $site->id)
                  ->where('name', $brancheType)
                  ->whereNull('parent_id') // <== This line filters top-level branches only
                  ->first();


        return response()->json([
            'branche' => $branche
        ]);
    }
}