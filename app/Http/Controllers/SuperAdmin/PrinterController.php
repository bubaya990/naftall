<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Printer;
use App\Models\Room;  // Assuming Room model is related to Location
use Illuminate\Http\Request;

class PrinterController extends Controller
{
    /**
     * Show the form to create a new printer.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Fetch all rooms
        $rooms = Room::all();
        return view('superadmin.materials.printercreate', compact('rooms'));
    }

    /**
     * Store a newly created printer in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'room_id' => 'required|integer|exists:rooms,id',  // Room is related to Location
        ]);

        // Create the new printer
        Printer::create([
            'name' => $request->name,
            'brand' => $request->brand,
            'model' => $request->model,
            'room_id' => $request->room_id,  // Link printer to room
        ]);

        // Redirect back to the printers index page with a success message
        return redirect()->route('superadmin.materials.printers.index')->with('success', 'Printer created successfully!');
    }
}
