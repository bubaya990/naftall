<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Branche;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Address;
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
        if (auth()->user()->role !== 'superadmin') {
    abort(403, 'Unauthorized access.');
}

         $superadmin = auth()->user(); 
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

    /* Delete a plan image by index */

    
    public function deletePlan(Branche $branche, $imageIndex)
    {         $superadmin = auth()->user(); 
if (auth()->user()->role !== 'superadmin') {
    abort(403, 'Unauthorized access.');
}

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
        'x' => 'required|numeric|between:0,100',
        'y' => 'required|numeric|between:0,100',
        'link_type' => 'required|in:room,corridor',
        'location_id' => 'required|exists:locations,id',
        'room_id' => 'required_if:link_type,room|exists:rooms,id',
        'corridor_id' => 'required_if:link_type,corridor|exists:corridors,id'
    ]);

    // Add debug logging
    \Log::debug('StoreImagePoint Request:', $request->all());

    try {
        $branche = Branche::findOrFail($request->branche_id);
        $links = $branche->plan_links ?? [];

        $entityId = $request->link_type === 'room' 
            ? $request->room_id
            : $request->corridor_id;

        if (!$entityId) {
            throw new \Exception('ID d\'entité manquant');
        }

        $url = route('superadmin.locations.view', [
            'location' => $request->location_id,
            'entityType' => $request->link_type,
            'entity' => $entityId
        ]);

        $links[] = [
            'image_index' => $request->image_index,
            'x' => $request->x,
            'y' => $request->y,
            'type' => $request->link_type,
            'entity_id' => $entityId,
            'url' => $url
        ];

        $branche->plan_links = $links;
        $branche->save();

        return response()->json([
            'success' => true,
            'message' => 'Point enregistré avec succès'
        ]);

    } catch (\Exception $e) {
        \Log::error('StoreImagePoint Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Erreur serveur: ' . $e->getMessage()
        ], 500);
    }
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


    /*  Delet link */


    public function deleteLink(Branche $branche, $linkIndex)
    {
        if (auth()->user()->role !== 'superadmin') {
    abort(403, 'Unauthorized access.');
}

        // Only superadmin can delete links
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Unauthorized action.');
        }
    
        $planLinks = $branche->plan_links ?? [];
    
        if (isset($planLinks[$linkIndex])) {
            unset($planLinks[$linkIndex]);
            $branche->plan_links = array_values($planLinks); // Re-index the array
            $branche->save();
    
            return back()->with('success', 'Link deleted successfully.');
        }
    
        return back()->with('error', 'Link not found.');
    }


}