<?php

namespace App\Http\Controllers\SuperAdmin;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Material;
use App\Models\Location;
use App\Models\Reclamation;
use Carbon\Carbon;


class SuperAdminDashboardController extends Controller
{
    public function superadmindashboard()
    {
        // Total counts
        $userCount = User::count();
        $materialCount = Material::count();
        $locationCount = Location::count();
        
        // Calculate growth percentages (last 30 days vs previous 30 days)
        $userGrowth = $this->calculateGrowth(User::class);
        $materialGrowth = $this->calculateGrowth(Material::class);
        
        // New locations in the last 30 days
        $newLocations = Location::where('created_at', '>=', now()->subDays(30))->count();
        $user = Auth::user();

        // Get unread messages count for the current user
        $unreadCount = $user ? $user->unreadMessages()->count() : 0;
        
        // Get latest 3 reclamations
        $latestReclamations = Reclamation::latest()->take(3)->get();

        return view('superadmin.dashboard', [
            'userCount' => $userCount,
            'materialCount' => $materialCount,
            'locationCount' => $locationCount,
            'userGrowth' => $userGrowth,
            'materialGrowth' => $materialGrowth,
            'newLocations' => $newLocations,
            'unreadCount' => $unreadCount,
            'latestReclamations' => $latestReclamations
        ]);
    }
    
    private function calculateGrowth($model)
    {
        // Current period (last 30 days)
        $currentCount = $model::where('created_at', '>=', now()->subDays(30))->count();
        
        // Previous period (30-60 days ago)
        $previousCount = $model::whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])
            ->count();
            
        // Avoid division by zero
        if ($previousCount === 0) {
            return $currentCount > 0 ? 100 : 0;
        }
        
        return round(($currentCount - $previousCount) / $previousCount * 100, 2);
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
