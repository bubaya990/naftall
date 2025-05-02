<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Branche;

class SiteController extends Controller
{

    public function showBranches($id, $brancheType)
    {
        $site = Site::findOrFail($id);
        
        $branche = $site->branches()
                        ->where('name', $brancheType)
                        ->whereNull('parent_id')
                        ->with('children')
                        ->firstOrFail();

        return view('superadmin.sites', [
            'site' => $site,
            'branche' => $branche
        ]);
    }

    public function showCarburant(Site $site)
    {
        return view('sites.carburant', [
            'site' => $site,
            'isSiege' => $site->name === 'Siege'
        ]);
    }
    
    public function showCommercial(Site $site)
    {
        return view('sites.commercial', [
            'site' => $site,
            'branche' => $site->branches()
                             ->where('name', 'Commercial')
                             ->firstOrFail()
        ]);
    }
    
    public function showAgence(Site $site)
    {
        return view('sites.agence', ['site' => $site]);
    }


    public function showSite(Site $site, $branchType = null, Branche $branch = null)
{
    $data = [
        'site' => $site,
        'branchType' => $branchType,
        'branch' => $branch
    ];

    // Special cases
    if ($branchType === 'agence') {
        $data['showAgencePlan'] = $site->name === 'Siege';
    } 
    elseif ($branchType === 'carburant') {
        $data['showFloorPlans'] = $site->name === 'Siege';
    }

    return view('superadmin.sites', $data);
}
public function show(Site $site, $branchType = null)
{
    $data = [
        'site' => $site,
        'branchType' => $branchType
    ];

    // Special handling for Siege
    if ($site->name === 'Siege') {
        if ($branchType === 'carburant') {
            $data['showFloorPlans'] = true;
        } elseif ($branchType === 'agence') {
            $data['showAgencePlan'] = true;
        }
    }

    return view('superadmin.sites', $data);
}


}