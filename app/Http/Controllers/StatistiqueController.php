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
use App\Models\Location;
use Illuminate\Http\Request;

class StatistiqueController extends Controller
{
    public function index()
    {
        // User Statistics
        $userCount = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $superadminCount = User::where('role', 'superadmin')->count();
        $leaderCount = User::where('role', 'leader')->count();
        $utilisateurCount = User::where('role', 'utilisateur')->count();

        // Material Statistics by type
        $materialCount = Material::count();
        $computerCount = Computer::count();
        $printerCount = Printer::count();
        $ipPhoneCount = IpPhone::count();
        $hotspotCount = Hotspot::count();
        
        // Material states
        $materialBon = Material::where('state', 'bon')->count();
        $materialDefectueux = Material::where('state', 'défectueux')->count();
        $materialHorsService = Material::where('state', 'hors_service')->count();

        // Reclamation Statistics
        $reclamationCount = Reclamation::count();
        $newReclamationCount = Reclamation::where('state', 'nouvelle')->count();
        $enCoursReclamationCount = Reclamation::where('state', 'en_cours')->count();
        $traiteeReclamationCount = Reclamation::where('state', 'traitée')->count();

        // Site Statistics
        $siteCount = Site::count();
        $locationCount = Location::count();

        // Get sites with basic counts
        $sites = Site::withCount([
            'locations'
        ])->get();

        // Add additional statistics to each site
        foreach ($sites as $site) {
            // User counts - using site_id directly
            $site->users_count = User::where('site_id', $site->id)->count();
            $site->admins_count = User::where('site_id', $site->id)
                ->where('role', 'admin')->count();
            $site->superadmins_count = User::where('site_id', $site->id)
                ->where('role', 'superadmin')->count();
            $site->leaders_count = User::where('site_id', $site->id)
                ->where('role', 'leader')->count();
            $site->utilisateurs_count = User::where('site_id', $site->id)
                ->where('role', 'utilisateur')->count();

            // Reclamation counts - using site_id through user relationship
            $site->reclamations_count = Reclamation::whereHas('user', function($query) use ($site) {
                $query->where('site_id', $site->id);
            })->count();
            
            $site->new_reclamations_count = Reclamation::whereHas('user', function($query) use ($site) {
                $query->where('site_id', $site->id);
            })->where('state', 'nouvelle')->count();
            
            $site->en_cours_reclamations_count = Reclamation::whereHas('user', function($query) use ($site) {
                $query->where('site_id', $site->id);
            })->where('state', 'en_cours')->count();
            
            $site->traitee_reclamations_count = Reclamation::whereHas('user', function($query) use ($site) {
                $query->where('site_id', $site->id);
            })->where('state', 'traitée')->count();

            // Material counts by type - using polymorphic relationship
            $site->computers_count = Material::where('materialable_type', Computer::class)
                ->where(function($query) use ($site) {
                    $query->whereHas('room.location', function($q) use ($site) {
                        $q->where('site_id', $site->id);
                    })->orWhereHas('corridor.location', function($q) use ($site) {
                        $q->where('site_id', $site->id);
                    });
                })->count();

            $site->printers_count = Material::where('materialable_type', Printer::class)
                ->where(function($query) use ($site) {
                    $query->whereHas('room.location', function($q) use ($site) {
                        $q->where('site_id', $site->id);
                    })->orWhereHas('corridor.location', function($q) use ($site) {
                        $q->where('site_id', $site->id);
                    });
                })->count();

            $site->ip_phones_count = Material::where('materialable_type', IpPhone::class)
                ->where(function($query) use ($site) {
                    $query->whereHas('room.location', function($q) use ($site) {
                        $q->where('site_id', $site->id);
                    })->orWhereHas('corridor.location', function($q) use ($site) {
                        $q->where('site_id', $site->id);
                    });
                })->count();

            $site->hotspots_count = Material::where('materialable_type', Hotspot::class)
                ->where(function($query) use ($site) {
                    $query->whereHas('room.location', function($q) use ($site) {
                        $q->where('site_id', $site->id);
                    })->orWhereHas('corridor.location', function($q) use ($site) {
                        $q->where('site_id', $site->id);
                    });
                })->count();

            // Material states count
            $site->bon_materials_count = Material::where('state', 'bon')
                ->where(function($query) use ($site) {
                    $query->whereHas('room.location', function($q) use ($site) {
                        $q->where('site_id', $site->id);
                    })->orWhereHas('corridor.location', function($q) use ($site) {
                        $q->where('site_id', $site->id);
                    });
                })->count();

            $site->defectueux_materials_count = Material::where('state', 'défectueux')
                ->where(function($query) use ($site) {
                    $query->whereHas('room.location', function($q) use ($site) {
                        $q->where('site_id', $site->id);
                    })->orWhereHas('corridor.location', function($q) use ($site) {
                        $q->where('site_id', $site->id);
                    });
                })->count();

            $site->hors_service_materials_count = Material::where('state', 'hors_service')
                ->where(function($query) use ($site) {
                    $query->whereHas('room.location', function($q) use ($site) {
                        $q->where('site_id', $site->id);
                    })->orWhereHas('corridor.location', function($q) use ($site) {
                        $q->where('site_id', $site->id);
                    });
                })->count();

            // Location types count
            $site->location_types = Location::where('site_id', $site->id)
                ->selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type');
        }

        // General diagram data
        $generalDiagramData = [
            'users' => [
                'Admin' => $adminCount,
                'SuperAdmin' => $superadminCount,
                'Leader' => $leaderCount,
                'Utilisateur' => $utilisateurCount
            ],
            'materials' => [
                'Computer' => $computerCount,
                'Printer' => $printerCount,
                'IP Phone' => $ipPhoneCount,
                'Hotspot' => $hotspotCount
            ],
            'reclamations' => [
                'Nouvelle' => $newReclamationCount,
                'En cours' => $enCoursReclamationCount,
                'Traitée' => $traiteeReclamationCount
            ]
        ];

        return view('statistiques', compact(
            'userCount', 'adminCount', 'superadminCount', 'leaderCount', 'utilisateurCount',
            'materialCount', 'computerCount', 'printerCount', 'ipPhoneCount', 'hotspotCount',
            'materialBon', 'materialDefectueux', 'materialHorsService',
            'reclamationCount', 'newReclamationCount', 'enCoursReclamationCount', 'traiteeReclamationCount',
            'siteCount', 'locationCount',
            'sites', 'generalDiagramData'
        ));
    }
}