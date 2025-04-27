<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Computer;
use App\Models\Material;
use App\Models\Room;

use Illuminate\Http\Request;

class ComputerController extends Controller
{
    public function create()
{
    $rooms = Room::all(); // or filter by site/location if needed
    return view('superadmin.materials.createcomputer', compact('rooms'));
}

public function store(Request $request)
{
    $request->validate([
        'hostname' => 'required|string|max:255',
        'serial_number' => 'required|string|max:255|unique:computers',
        'brand' => 'required|string',
        'model' => 'required|string',
        'operating_system' => 'required|string',
        'room_id' => 'required|exists:rooms,id',
    ]);

    Computer::create($request->all());

    return redirect()->route('superadmin.materials.index')->with('success', 'Computer added successfully!');
}

}