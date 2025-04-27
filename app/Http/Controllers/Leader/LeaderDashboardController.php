<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaderDashboardController extends Controller
{
    public function leaderdashboard()
    {
        // Logic for handling Leader dashboard
        return view('leader.dashboard');
    }

    public function gestionLocalite()
    {
        // Logic for managing localities for the Leader role
        return view('leader.gestion-localite');
    }
}
