<?php

namespace App\Http\Controllers\Utilisateur;

use App\Http\Controllers\Controller;

class UtilisateurController extends Controller
{
    public function dashboard()
    {
        return view('utilisateur.dashboard');
    }
}
