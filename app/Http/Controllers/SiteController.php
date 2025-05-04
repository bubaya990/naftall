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

                        return view('superadmin.branches.show', compact('site', 'brancheType'));

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


    public function showSite(Site $site, $brancheType = null, Branche $branch = null)
{
    $data = [
        'site' => $site,
        'brancheType' => $brancheType,
        'branche' => $branch
    ];

    // Special cases
    if ($brancheType === 'agence') {
        $data['showAgencePlan'] = $site->name === 'Siege';
    }
    elseif ($brancheType === 'carburant') {
        $data['showFloorPlans'] = $site->name === 'Siege';
    }

    return view('superadmin.sites', $data);
}
public function show(Site $site, $brancheType = null)
{
    $data = [
        'site' => $site,
        'brancheType' => $brancheType
    ];

    // Special handling for Siege
    if ($site->name === 'Siege') {
        if ($brancheType === 'carburant') {
            $data['showFloorPlans'] = true;
        } elseif ($brancheType === 'agence') {
            $data['showAgencePlan'] = true;
        }
    }

    return view('superadmin.sites', $data);
}

public function showBranche(Site $site, $brancheType)
{
    $branche = $site->branches()
                    ->where('name', $brancheType)
                    ->whereNull('parent_id')
                    ->with('children')
                    ->firstOrFail();

    return view('superadmin.sites', [
        'site' => $site,
        'brancheType' => $brancheType,
        'branche' => $branche
    ]);
}

public function showBrancheDetail(Site $site, $brancheType, Branche $branche)
{
    // VÃ©rifier que la branche appartient bien au site
    if ($branche->site_id !== $site->id) {
        abort(404);
    }

    return view('superadmin.sites', [
        'site' => $site,
        'brancheType' => $brancheType,
        'branche' => $branche,
        'showAgencePlan' => $brancheType === 'agence' && $site->name === 'Siege',
        'showFloorPlans' => $brancheType === 'carburant' && $site->name === 'Siege'
    ]);
}

}
