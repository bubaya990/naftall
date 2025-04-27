<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function utilisateurs()
    {
        return view('admin.utilisateurs');
    }

   // In AdminController.php
public function gestionMaterial()
{
    return view('admin.gestion-material');
}

}
