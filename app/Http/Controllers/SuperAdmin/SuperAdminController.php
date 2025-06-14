<?php

namespace App\Http\Controllers\SuperAdmin;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Site;
use Illuminate\Support\Facades\Hash;
use App\Models\Material;
use App\Models\Message;
use App\Models\Branche;
use App\Models\Reclamation;
use Illuminate\Support\Str;



class SuperAdminController extends Controller
{
   
  
   //utilisateurs list
    public function utilisateurs()
    {
        $users = User::all(); 
        return view('superadmin.utilisateurs.utilisateurs', compact('users'));
    }

   //cree user page 
    public function create()
    {

        //Authtefication superadmin only
        if (auth()->user()->role !== 'superadmin') 
            {
             abort(403, 'Unauthorized access.');
            }

        $sites = Site::all(); 
        $branches = Branche::whereIn('name', ['Commercial', 'Carburant'])->get();
        
        return view('superadmin.utilisateurs.create', compact('sites', 'branches'));
    }
    

    //stor and creat user  

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'superadmin')
         {
         abort(403, 'Unauthorized access.');
        }

        //verifier si est correct

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,utilisateur,superadmin,leader',
            'site_id' => 'required|exists:sites,id',
            'branche_id' => 'required|exists:branches,id', 
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'site_id' => $request->site_id,
            'password' => Hash::make($request->password),
            'branche_id' => $request->branche_id,
        ]);
    
        return redirect()->route('superadmin.utilisateurs')->with('success', 'User created successfully.');
    }
    

 

    //page edit user

    public function edit($id)
    {
        if (auth()->user()->role !== 'superadmin')
        {
             abort(403, 'Unauthorized access.');
        }

        $user = User::findOrFail($id);
        $sites = Site::all(); // Get all sites to populate the dropdown
        return view('superadmin.utilisateurs.edit', compact('user', 'sites'));
    }

    //update user function (role)

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'superadmin')
        {
            abort(403, 'Unauthorized access.');
        }

        // validate 
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

    //delet user 
    public function destroy($id)
    {
        if (auth()->user()->role !== 'superadmin')
        {
             abort(403, 'Unauthorized access.');
        }
        
        //find user
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return redirect()->route('superadmin.utilisateurs')
                             ->with('success', 'User deleted successfully.');
        }

        return redirect()->route('superadmin.utilisateurs')
                         ->with('error', 'User not found.');
    }

    //material festion (old)
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
    



//list reclamation page
public function reclamations()
{
    $reclamations = Reclamation::with('user')->latest()->paginate(100);
    return view('superadmin.reclamations.reclamations', compact('reclamations'));
}

//add reclamation
public function addreclamation()
{
    $users = User::all();

    return view('superadmin.reclamations.addreclamation', compact('users'));
  
}

//add reclamation and stor it function
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

//delet reclamation

public function destroyReclamation($id)
{
    if (auth()->user()->role !== 'superadmin') {
    abort(403, 'Unauthorized access.');
}
    $reclamation = Reclamation::find($id);

    if ($reclamation) {
        $reclamation->delete();
        return redirect()->route('superadmin.reclamations')
                         ->with('success', 'Reclamation deleted successfully.');
    }

    return redirect()->route('superadmin.reclamations')
                     ->with('error', 'Reclamation not found.');
}


//nadd
public function getUnreadCount()
{
    $user = auth()->user();
    $unreadCount = $user ? $user->unreadMessages()->count() : 0;
    return response()->json(['count' => $unreadCount]);
}
//nadd
public function markAsSeen(Request $request)
{
    $messageId = $request->input('messageId');
    
    if ($messageId === 'all') {
        auth()->user()->unreadMessages()->update(['seen' => true]);
    } else {
        auth()->user()->unreadMessages()->where('id', $messageId)->update(['seen' => true]);
    }
    
    return response()->json(['success' => true]);
}

//user role update
public function updateRole(Request $request, User $user)
{
    $request->validate([
        'role' => 'required|in:superadmin,admin,leader,utilisateur'
    ]);

    $user->update(['role' => $request->role]);

    return response()->json([
        'success' => true,
        'message' => 'Role updated successfully'
    ]);
}






//count notification (nouvell reclcmation)& drop down reclamation(3only)
 public function dashboard()
    {
        // Count new (unread) reclamations (state = 'nouvelle')
        $newReclamationsCount = Reclamation::where('state', 'nouvelle')->count();

        // Latest 3 reclamations for dropdown
        $latestReclamations = Reclamation::with('user')
            ->latest()
            ->limit(3)
            ->get();

        return view('superadmin.dashboard', [
            'newReclamationsCount' => $newReclamationsCount,
            'latestReclamations' => $latestReclamations,
            
        ]);
    }

    
    public function getNewReclamationsCount()
    {
        $count = Reclamation::where('state', 'nouvelle')->count();
        return response()->json(['count' => $count]);
    }

  

    // API: Mark a reclamation as read (for AJAX).
     
    public function markAsRead($id)
    {
        $reclamation = Reclamation::findOrFail($id);
        
        if ($reclamation->state === 'nouvelle') {
            $reclamation->update([
                'state' => 'en_cours',
                'handler_id' => auth()->id(),
                'handled_at' => now()
            ]);
        }

        return response()->json(['success' => true]);
    }








    public function showReclamation($id)
{
    $reclamation = Reclamation::with(['user', 'messages', 'messages.sender', 'handler'])
        ->findOrFail($id);
    
    return view('superadmin.reclamations.view', compact('reclamation'));
}


//Update the status of a reclamation.
 
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

 //Store a message for a reclamation.
 
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




 //Delete treated reclamations from last month ou treter

public function deleteTreated(Request $request)
{
    if (auth()->user()->role !== 'superadmin') {
        abort(403, 'Unauthorized access.');
    }

    $scope = $request->input('scope');
    $query = Reclamation::where('state', 'traitée');

    if ($scope === 'last_month') {
        $firstDayLastMonth = now()->subMonth()->startOfMonth()->toDateString();
        $lastDayLastMonth = now()->subMonth()->endOfMonth()->toDateString();
        $query->whereBetween('date_R', [$firstDayLastMonth, $lastDayLastMonth]);
    }

    $deletedCount = $query->delete();

    $message = match($scope) {
        'last_month' => "$deletedCount réclamations traitées du mois précédent supprimées.",
        'all' => "$deletedCount réclamations traitées supprimées.",
        default => "Suppression effectuée."
    };

    return redirect()->route('superadmin.reclamations')->with('success', $message);
}
}