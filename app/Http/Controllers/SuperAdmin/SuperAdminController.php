<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Site;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Message;
use App\Models\Branche;
use App\Models\Reclamation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class SuperAdminController extends Controller
{
    /**
     * Display the SuperAdmin dashboard.
     */
    public function dashboard()
    {
        return view('superadmin.dashboard');
    }

    /**
     * Display a listing of the users.
     */
    public function utilisateurs()
    {
        $users = User::all(); // You can add pagination or filters if needed
        return view('superadmin.utilisateurs.utilisateurs', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $sites = Site::all(); // Get all sites from the database
        // Récupère uniquement les noms de branches uniques
    $branches = Branche::select('name')
    ->distinct()
    ->get();
        
      
        return view('superadmin.utilisateurs.create', compact('sites', 'branches'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Unique email constraint
            'role' => 'required|in:admin,utilisateur,superadmin,leader',
            'site_id' => 'required|exists:sites,id', // Ensure site exists
            'branche_name' => 'required|in:carburant,commercial', // Validation par nom
        ]);

        // Trouve la branche correspondant au nom (première occurrence)
    $branche = Branche::where('name', $request->branche_name)->first();

        // Create the user and insert into the database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'site_id' => $request->site_id, // assuming 'site_id' is the foreign key
            'password' => bcrypt('defaultpassword'), // Setting a default password (change as needed)
            'branche_id' => $branche->id, // Utilise l'ID de la branche trouvée
        ]);

        // Redirect back with success message
        return redirect()->route('superadmin.utilisateurs')
                         ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $sites = Site::all(); // Get all sites to populate the dropdown
        return view('superadmin.utilisateurs.edit', compact('user', 'sites'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // Ignore current email for uniqueness check
            'role' => 'required|in:admin,utilisateur,superadmin,leader',
            'site_id' => 'required|exists:sites,id', // Ensure site exists
        ]);

        // Find and update the user
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'site_id' => $request->site_id,
        ]);

        // Redirect back with success message
        return redirect()->route('superadmin.utilisateurs')
                         ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return redirect()->route('superadmin.utilisateurs')
                             ->with('success', 'User deleted successfully.');
        }

        return redirect()->route('superadmin.utilisateurs')
                         ->with('error', 'User not found.');
    }

    /**
     * Display the material management view.
     */
    public function gestionMaterial()
    {
        // Use consistent field name: material_type
        $totalComputers = Material::where('materials_type', 'computer')->count();
        $totalPrinters = Material::where('materials_type', 'printer')->count();
        $totalHotspots = Material::where('materials_type', 'hotspot')->count();
        $totalIpPhones = Material::where('materials_type', 'ip-phone')->count();
    
        $sites = Site::with(['locations.rooms.materials', 'locations.corridors.materials'])
            ->get()
            ->map(function ($site) {
                $counts = [
                    'computer' => 0,
                    'printer' => 0,
                    'hotspot' => 0,
                    'ip-phone' => 0,
                ];
    
                foreach ($site->locations as $location) {
                    foreach ($location->rooms as $room) {
                        foreach ($room->materials as $material) {
                            if (isset($counts[$material->materials_type])) {
                                $counts[$material->materials_type]++;
                            }
                        }
                    }
                    foreach ($location->corridors as $corridor) {
                        foreach ($corridor->materials as $material) {
                            if (isset($counts[$material->materials_type])) {
                                $counts[$material->materials_type]++;
                            }
                        }
                    }
                }
    
                return [
                    'id' => $site->id,
                    'name' => $site->name,
                    'computers_count' => $counts['computer'],
                    'printers_count' => $counts['printer'],
                    'hotspots_count' => $counts['hotspot'],
                    'ip_phones_count' => $counts['ip-phone'],
                ];
            });
    
        return view('superadmin.materials.gestion-material', compact(
            'totalComputers',
            'totalPrinters',
            'totalHotspots',
            'totalIpPhones',
            'sites'
        ));
    }
    
 
    /**
     * Display the localite management view.
     */
    public function gestionLocalite()
    {
    
        $sites = \App\Models\Site::with(['locations' => function($query) {
            $query->with('floor');
        }])->get();
    
        return view('superadmin.locations.gestion-localite', compact('sites'));
        
    }

    //the site in dashboard
    public function cbr() {
        // logic for CBR page
        return view('superadmin.cbr');
    }
    
    public function com() {
        // logic for COM page
        return view('superadmin.com');
    }
    public function showCom()
    {
        // Get branches with their associated site where the name is 'commercial'
        $branches = Branche::with('site')->where('name', 'commercial')->get();
        
        return view('superadmin.com', compact('branches'));
    }
    
    public function showCbr()
    {
        // Get branches with their associated site where the name is 'carburant'
        $branches = Branche::with('site')->where('name', 'carburant')->get();
        return view('superadmin.cbr', compact('branches'));
    }
    



/**
 * Display a listing of reclamations.
 */
public function reclamations()
{
    $reclamations = Reclamation::with('user')->latest()->paginate(10);
    return view('superadmin.reclamations.reclamations', compact('reclamations'));
}

/**
 * Show the form for creating a new reclamation.
 */
public function addreclamation()
{
    $users = User::all();

    return view('superadmin.reclamations.addreclamation', compact('users'));
  
}

/**
 * Store a newly created reclamation in storage.
 */
public function storeReclamation(Request $request)
{
    // Assuming 'state' is an optional field and you want to default it to a value like 'pending'
    $reclamation = new Reclamation([
        'num_R' => $request->num_R,
        'date_R' => $request->date_R,
        'definition' => $request->definition,
        'message' => $request->message,
        'user_id' => $request->user_id,
        'receiver_id' => $request->receiver_id,
        'state' => 'pending', // Default value for the state
    ]);

    $reclamation->save();

    return redirect()->route('superadmin.reclamations')->with('success', 'Reclamation created successfully!');
}

/**
 * Display the specified reclamation.
 */
public function showReclamation($id)
{
    $reclamation = Reclamation::with(['user', 'messages'])->findOrFail($id);
    return view('superadmin.reclamations.reclamations', compact('reclamation'));
}
public function destroyReclamation($id)
{
    $reclamation = Reclamation::find($id);

    if ($reclamation) {
        $reclamation->delete();
        return redirect()->route('superadmin.reclamations')
                         ->with('success', 'Reclamation deleted successfully.');
    }

    return redirect()->route('superadmin.reclamations')
                     ->with('error', 'Reclamation not found.');
}

/**
 * Add a message to a reclamation.
 */
public function storeMessage(Request $request, $reclamationId)
{
    $request->validate([
        'message' => 'required|string',
    ]);

    $reclamation = Reclamation::findOrFail($reclamationId);

    Message::create([
        'message' => $request->message,
        'reclamation_id' => $reclamation->id,
        'sender_id' => Auth::id(),
        'receiver_id' => $reclamation->user_id,
        'seen' => false,
    ]);

    return redirect()->back()
        ->with('success', 'Message envoyé avec succès!');
}
public function showReclamations()
{
    $reclamations = Reclamation::with('user')->latest()->paginate(10);
    return view('superadmin.reclamations.reclamations', compact('reclamations'));
}
/**
 * Mark a message as seen.
 */
public function markAsSeen($messageId)
{
    $message = Message::findOrFail($messageId);
    $message->update(['seen' => true]);
    
    return response()->json(['success' => true]);
}

/**
 * Get unread messages count (for AJAX).
 */
public function getUnreadCount()
{
    $count = Auth::user()->unreadMessages()->count();
    return response()->json(['count' => $count]);
}  
}