<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Site;
use App\Models\Material;
use App\Models\Computer;
use App\Models\Printer;
use App\Models\IpPhone;
use App\Models\Hotspot;
use App\Models\Reclamation;
use App\Models\Branche;
use App\Models\Location;
use Illuminate\Http\Request;

class StatistiqueController extends Controller
{
    public function index()
{
    // User Statistics
    $userCount = User::count();
    $adminCount = User::where('role', 'admin')->count();
    $leaderCount = User::where('role', 'leader')->count();

    // Material Statistics
    $materialCount = Material::count();
    $computerCount = Computer::count();
    $printerCount = Printer::count();
    $ipPhoneCount = IpPhone::count();
    $hotspotCount = Hotspot::count();

    // Replace 'status' with 'state'
    $materialFonctionnel = Material::where('state', 'fonctionnel')->count(); // Changed 'status' to 'state'
    $materialPanne = Material::where('state', 'en panne')->count(); // Changed 'status' to 'state'
    $materialMaintenance = Material::where('state', 'en maintenance')->count(); // Changed 'status' to 'state'

    // Reclamation Statistics
    $reclamationCount = Reclamation::count();
    $newReclamationCount = Reclamation::where('state', 'new')->count(); // Changed 'status' to 'state'
    $resolvedReclamationCount = Reclamation::where('state', 'resolved')->count(); // Changed 'status' to 'state'
    $sitesWithReclamations = Site::withCount('reclamations')->get();

    $reclamationStatusCounts = [
        'new' => Reclamation::where('state', 'new')->count(), // Changed 'status' to 'state'
        'in_progress' => Reclamation::where('state', 'in_progress')->count(), // Changed 'status' to 'state'
        'resolved' => Reclamation::where('state', 'resolved')->count(), // Changed 'status' to 'state'
        'closed' => Reclamation::where('state', 'closed')->count(), // Changed 'status' to 'state'
    ];

    // Location Statistics
    $siteCount = Site::count();
    $brancheCount = Branche::count();
    $locationCount = Location::count();
    $superadminCount = User::where('role', 'superadmin')->count();
    $utilisateurCount = User::where('role', 'utilisateur')->count();
    
    return view('statistiques', compact(
        'userCount', 'adminCount', 'leaderCount',
        'materialCount', 'computerCount', 'printerCount', 'ipPhoneCount', 'hotspotCount',
        'materialFonctionnel', 'materialPanne', 'materialMaintenance',  
        'reclamationCount', 'newReclamationCount', 'resolvedReclamationCount', 'reclamationStatusCounts',
        'siteCount', 'brancheCount', 'locationCount',
        'superadminCount', 'utilisateurCount','sitesWithReclamations'
    ));
}

}