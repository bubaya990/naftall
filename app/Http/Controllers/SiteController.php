<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Branche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteController extends Controller
{
    /**
     * Display a site and its specific branch.
     */
    public function show(Site $site, $brancheType)
    {
        $branche = Branche::where('site_id', $site->id)
                          ->where('name', $brancheType)
                          ->first();

        if (!$branche) {
            abort(404, "Branche '{$brancheType}' not found for this site.");
        }

        $planImages = $branche->plan_images ?? [];
        $planLinks = $branche->plan_links ?? [];

        return view('superadmin.sites', compact('site', 'branche', 'planImages', 'planLinks'));
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
            $path = $plan->store('plan_images', 'public');
            $uploadedPaths[] = $path;
        }

        $existingPlans = $branche->plan_images ?? [];
        $branche->plan_images = array_merge($existingPlans, $uploadedPaths);
        $branche->save();

        return back()->with('success', 'Plans uploaded successfully.');
    }

    /**
     * Delete a plan image by index.
     */
    public function deletePlan(Branche $branche, $imageIndex)
    {
        $planImages = $branche->plan_images ?? [];

        if (isset($planImages[$imageIndex])) {
            Storage::delete('public/' . $planImages[$imageIndex]);
            array_splice($planImages, $imageIndex, 1);

            // Also remove plan links related to this image index
            $planLinks = $branche->plan_links ?? [];
            $planLinks = array_filter($planLinks, fn ($link) => $link['image_index'] != $imageIndex);
            $branche->plan_links = array_values($planLinks);

            $branche->plan_images = $planImages;
            $branche->save();

            return back()->with('success', 'Plan deleted successfully.');
        }

        return back()->with('error', 'Plan not found.');
    }

    /**
     * Store a link point on a plan image.
     */
   public function storeImagePoint(Request $request)
{
    $request->validate([
        'branche_id' => 'required|exists:branches,id',
        'image_index' => 'required|integer',
        'x' => 'required|numeric|between:0,100', // Percentage values
        'y' => 'required|numeric|between:0,100',
        'link_type' => 'required|in:room,corridor',
        'room_id' => 'required_if:link_type,room|exists:rooms,id',
        'corridor_id' => 'required_if:link_type,corridor|exists:corridors,id'
    ]);

    $branche = Branche::findOrFail($request->branche_id);
    $links = $branche->plan_links ?? [];

    // Get the first location (you may need to adjust this)
    $location = $branche->site->locations->first();
    if (!$location) {
        return back()->with('error', 'No location found for this site');
    }

    // Create URL based on your existing routes
    $url = $request->link_type === 'room'
        ? route('superadmin.locations.rooms.view', [
            'location' => $location->id,
            'room' => $request->room_id
          ])
        : route('superadmin.locations.corridors.view', [
            'location' => $location->id,
            'corridor' => $request->corridor_id
          ]);

    $links[] = [
        'image_index' => $request->image_index,
        'x' => $request->x,
        'y' => $request->y,
        'type' => $request->link_type,
        'entity_id' => $request->link_type === 'room' ? $request->room_id : $request->corridor_id,
        'url' => $url
    ];

    $branche->plan_links = $links;
    $branche->save();

    return back()->with('success', 'Point saved successfully. Click the marker to visit.');
}

    /**
     * Load carburant branch.
     */
    public function showCarburant(Site $site)
    {
        return $this->show($site, 'carburant');
    }

    /**
     * Load commercial branch.
     */
    public function showCommercial(Site $site)
    {
        return $this->show($site, 'commercial');
    }

    /**
     * Load agence branch.
     */
    public function showAgence(Site $site)
    {
        return $this->show($site, 'agence');
    }

    /**
     * Used in AJAX to get branche info.
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
