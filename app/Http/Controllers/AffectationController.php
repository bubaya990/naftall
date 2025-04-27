<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AffectationController extends Controller
{
    /**
     * Display a listing of affectations.
     */
    public function index()
    {
        return view('leader.affectations.index');
    }
}
