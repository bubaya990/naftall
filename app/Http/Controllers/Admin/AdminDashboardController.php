<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function admindashboard()
    {
        // You can add any data fetching logic here for the Admin dashboard
        return view('admin.dashboard');
    }
    public function parametre()
    {
        // Your logic to display the "parametre" page here
        return view('admin.parametre');  // Make sure you have this Blade view
    }
    public function statistiques()
    {
        // Your logic to display statistics here
        return view('admin.statistiques');  // Make sure you have this Blade view
    }
    public function utilisateurs()
    {
        // Logic for handling user management for Admin
        return view('admin.utilisateurs');
    }
    // In AdminController.php
public function gestionMaterial()
{
    return view('admin.gestion-material');
}

}
