<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function parametres()
    {
        return view('parametres');
    }

    public function statistiques()
    {
        return view('statistiques');
    }
}
