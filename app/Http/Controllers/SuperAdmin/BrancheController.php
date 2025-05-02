<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Branche;
use App\Models\Site;

class BrancheController extends Controller
{


    public function carburantSites()
    {
        // Get all carburant branches with their sites
        $carburantBranches = Branche::with(['site'])
            ->where('name', 'Carburant') // Make sure this matches exactly how it's stored in DB
            ->get()
            ->filter(function($branche) {
                return $branche->site !== null; // Only include branches with sites
            })
            ->unique('site_id'); // Get unique sites
    
        // Debug output (remove after testing)
        // dd($carburantBranches);
    
        return view('superadmin.cbr', compact('carburantBranches'));
    }


    public function commercialStructure()
    {
        // Get all commercial branches with their children
        $commercialBranches = Branche::with(['children.children'])
            ->where('name', 'Commercial')
            ->get();
    
        // Group branches by site
        $branchesBySite = [];
        foreach ($commercialBranches as $branch) {
            $branchesBySite[$branch->site_id][] = $branch;
        }
    
        // Get all sites with commercial branches
        $sites = Site::whereIn('id', array_keys($branchesBySite))->get();
    
        return view('superadmin.com', [
            'branchesBySite' => $branchesBySite,
            'sites' => $sites
        ]);
    }
}