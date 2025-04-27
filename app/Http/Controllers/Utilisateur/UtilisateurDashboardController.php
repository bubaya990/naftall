<?php

namespace App\Http\Controllers\Utilisateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UtilisateurDashboardController extends Controller
{
    public function utilisateuredashboard()
    {
        // Logic for handling the Utilisateur dashboard
        return view('utilisateur.dashboard');
    }
}
