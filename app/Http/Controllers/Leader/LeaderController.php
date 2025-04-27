<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;

class LeaderController extends Controller
{
    public function dashboard()
    {
        return view('leader.dashboard');
    }

    public function utilisateurs()
    {
        return view('leader.utilisateurs');
    }

    public function gestionMaterial()
    {
        return view('leader.gestion-material');
    }

    public function gestionLocalite()
    {
        return view('leader.gestion-localite');
    }
}
