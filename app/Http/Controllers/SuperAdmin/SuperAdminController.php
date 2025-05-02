<?php

namespace App\Http\Controllers\SuperAdmin;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Site;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Message;
use App\Models\Branche;
use App\Models\Reclamation;
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
        
        // Get only 'Commercial' and 'Carburant' branches
        $branches = Branche::whereIn('name', ['Commercial', 'Carburant'])->get();
        
        return view('superadmin.utilisateurs.create', compact('sites', 'branches'));
    }
    

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,utilisateur,superadmin,leader',
            'site_id' => 'required|exists:sites,id',
            'branche_id' => 'required|exists:branches,id', // Validate branche_id
        ]);
    
        // Create the user with the selected branche_id
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'site_id' => $request->site_id,
            'password' => bcrypt('defaultpassword'),
            'branche_id' => $request->branche_id,
        ]);
    
        return redirect()->route('superadmin.utilisateurs')->with('success', 'User created successfully.');
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
   // For Commercial page
public function com()
{
    $commercialBranches = Branche::with(['children.children.children'])
        ->where('name', 'commercial')
        ->get();
    
    return view('superadmin.com', compact('commercialBranches'));
}

// For Carburant page
public function cbr()
{
    $carburantBranches = Branche::with('site')
        ->where('name', 'carburant')
        ->get();
    
    return view('superadmin.cbr', compact('carburantBranches'));
}
    public function showCom()
    {
        try {
            $commercialBranches = Branche::with(['children.children.children'])
                ->where('name', 'commercial')
                ->get();
                
            return view('superadmin.com', compact('commercialBranches'));
            
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
            abort(500, 'Could not load commercial branches');
        }
    }
    
    
    public function showCbr()
    {
        $carburantBranches = Branche::where('name', 'carburant')
            ->with('site') // Make sure this relationship exists in your Branche model
            ->get();
        
        return view('superadmin.cbr', compact('carburantBranches'));
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
        'state' => 'nouvelle', // Default value for the state
    ]);

    $reclamation->save();

    return redirect()->route('superadmin.reclamations')->with('success', 'Reclamation created successfully!');
}

/**
 * Display the specified reclamation.
 */

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

public function showReclamations()
{
    $reclamations = Reclamation::with('user')->latest()->paginate(10);
    return view('superadmin.reclamations.reclamations', compact('reclamations'));
}
/**
 * Mark a message as seen.
 */

/**
 * Display the specified reclamation.
 */
public function showReclamation($id)
{
    $reclamation = Reclamation::with(['user', 'messages', 'messages.sender', 'handler'])
        ->findOrFail($id);
    
    return view('superadmin.reclamations.view', compact('reclamation'));
}
/**
 * Update the status of a reclamation.
 */
// In app/Http/Controllers/SuperAdmin/SuperAdminController.php

public function updateStatus(Request $request, $id, $status)
{
    $validStatuses = ['nouvelle', 'en_cours', 'traitée'];
    
    if (!in_array($status, $validStatuses)) {
        return redirect()->back()->with('error', 'Statut invalide');
    }

    $reclamation = Reclamation::findOrFail($id);
    
    if ($reclamation->state === 'nouvelle' && $status === 'en_cours') {
        $reclamation->update([
            'state' => 'en_cours',
            'handler_id' => auth()->id(),
            'handled_at' => now()
        ]);
    }
    elseif ($reclamation->state === 'en_cours' && $status === 'traitée' && $reclamation->handler_id === auth()->id()) {
        $reclamation->update([
            'state' => 'traitée',
            'completed_at' => now()
        ]);
    } else {
        return redirect()->back()->with('error', 'Action non autorisée');
    }

    return redirect()->back()->with('success', 'Statut mis à jour avec succès');
}
/**
 * Store a message for a reclamation.
 */
public function storeMessage(Request $request, $reclamationId)
{
    $reclamation = Reclamation::findOrFail($reclamationId);
    
    if ($reclamation->state === 'traitée') {
        return redirect()->back()->with('error', 'Impossible d\'ajouter un message à une réclamation traitée');
    }

    $request->validate([
        'message' => 'required|string',
    ]);

    Message::create([
        'message' => $request->message,
        'reclamation_id' => $reclamation->id,
        'sender_id' => Auth::id(),
        'receiver_id' => $reclamation->user_id,
        'seen' => false,
    ]);

    return redirect()->back()->with('success', 'Message envoyé avec succès');
}

public function getUnreadCount()
{
    $user = auth()->user();
    $unreadCount = $user ? $user->unreadMessages()->count() : 0;
    return response()->json(['count' => $unreadCount]);
}

public function markAsSeen(Request $request)
{
    // Mark all messages as seen or a specific one if messageId is provided
    $messageId = $request->input('messageId');
    
    if ($messageId === 'all') {
        auth()->user()->unreadMessages()->update(['seen' => true]);
    } else {
        auth()->user()->unreadMessages()->where('id', $messageId)->update(['seen' => true]);
    }
    
    return response()->json(['success' => true]);
}



}