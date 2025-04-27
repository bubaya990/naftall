<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Material;
use App\Models\Location;


class SuperAdminDashboardController extends Controller
{
    public function superadmindashboard()
    {
        $userCount = User::count();
        $materialCount = Material::count();
        $locationCount = Location::count();
    
        // Optional demo growth values — replace with real data if needed
        $userGrowth = 10;        // Example: 10% growth
        $materialGrowth = 5;     // Example: 5% growth
        $newLocations = 2;       // New locations this month
        return view('superadmin.dashboard', [
            'userCount' => User::count() ?? 0,
            'materialCount' => Material::count() ?? 0,
            'locationCount' => Location::count() ?? 0,
            'userGrowth' => 10,    // Default growth values
            'materialGrowth' => 5,
            'newLocations' => 2
        ]);
        return view('superadmin.dashboard');
    }

    // Gestion Localité
    public function gestionLocalite()
    {
        return view('superadmin.gestion_localite');
    }

    // Statistiques
    public function statistiques()
    {
        return view('statistiques');
    }

    // Paramètres
    public function parametres()
    {
        return view('parametres');
    }
    public function gestionMaterial()
{
    return view('superadmin.gestion_material');
}


public function utilisateurs()
{
    return view('superadmin.utilisateurs');
}




}
