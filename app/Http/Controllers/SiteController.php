<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Branche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteController extends Controller
{
    /**
     * Display main site view
     */
    public function show(Site $site, $brancheType)
    {
        $branche = Branche::where('site_id', $site->id)
                          ->where('name', $brancheType)
                          ->first();

        if (!$branche) {
            abort(404, "Branche '{$brancheType}' not found for this site.");
        }

        return view('superadmin.sites', compact('site', 'branche'));
    }

    /**
     * Handle uploading plan images for a branch.
     */
    public function uploadPlans(Request $request, Branche $branche)
{
    $request->validate([
        'plans.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096'
    ]);

    $uploadedPaths = [];

    foreach ($request->file('plans') as $plan) {
        // Store in public disk (creates in storage/app/public)
        $path = $plan->store('plan_images', 'public');
        $uploadedPaths[] = $path; // No need to modify path
    }

    $existingPlans = $branche->plan_images ?? [];
    $branche->plan_images = array_merge($existingPlans, $uploadedPaths);
    $branche->save();

    return back()->with('success', 'Plans uploaded successfully.');
}

    /**
     * Delete a plan image
     */
    public function deletePlan(Branche $branche, $imageIndex)
    {
        $planImages = $branche->plan_images ?? [];
        
        if (isset($planImages[$imageIndex])) {
            // Delete from storage
            Storage::delete('public/' . $planImages[$imageIndex]);
            
            // Remove from array
            array_splice($planImages, $imageIndex, 1);
            
            // Update database
            $branche->plan_images = $planImages;
            $branche->save();
            
            return back()->with('success', 'Plan deleted successfully.');
        }

        return back()->with('error', 'Plan not found.');
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

        $branche = Branche::where('site_id', $request->site)
                  ->where('name', $request->brancheType)
                  ->whereNull('parent_id')
                  ->first();

        return response()->json([
            'branche' => $branche
        ]);
    }
}