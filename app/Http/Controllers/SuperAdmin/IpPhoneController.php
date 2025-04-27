<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IpPhone;
use App\Models\Room;
use App\Models\Corridor;

class IpPhoneController extends Controller
{
    public function create()
    {
        $rooms = Room::all();
        $corridors = Corridor::all();

        return view('superadmin.materials.ipphonecreate', compact('rooms', 'corridors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mac_address' => 'required|string',
            'brand' => 'required|string',
            'model' => 'required|string',
            'location_type' => 'required|in:room,corridor',
            'location_id' => 'required|integer',
        ]);

        IpPhone::create([
            'mac_address' => $request->mac_address,
            'brand' => $request->brand,
            'model' => $request->model,
            'location_type' => $request->location_type,
            'location_id' => $request->location_id,
        ]);

        return redirect()->route('superadmin.dashboard')->with('success', 'IP Phone added successfully!');
    }
}
