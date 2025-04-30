<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Utilisateur\UtilisateurDashboardController;
use App\Http\Controllers\Leader\LeaderDashboardController;
use App\Http\Controllers\Leader\LeaderController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\SuperAdmin\MaterialController;
use App\Http\Controllers\SuperAdmin\LocationController;
use App\Http\Controllers\SuperAdmin\BrancheController;
use App\Http\Controllers\MaterielController;
use App\Http\Controllers\AffectationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;

use App\Models\Floor;


// Public Route
Route::get('/', function () {
    return view('welcome');
});

// Auth
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Common
    Route::get('/informations', [InformationController::class, 'informations'])->name('informations');
    Route::get('/parametres', [CommonController::class, 'parametres'])->name('parametre');
    Route::get('/statistiques', [CommonController::class, 'statistiques'])->name('statistiques');

    // Dashboards
    Route::get('/utilisateur/dashboard', [UtilisateurDashboardController::class, 'utilisateuredashboard'])->name('utilisateur.dashboard');
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');
    Route::get('/leader/dashboard', [LeaderDashboardController::class, 'leaderdashboard'])->name('leader.dashboard');
    Route::get('/admin/dashboard', [AdminController::class, 'admindashboard'])->name('admin.dashboard');

});
 // SuperAdmin Management
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    // User Management
    Route::get('/utilisateurs', [SuperAdminController::class, 'utilisateurs'])->name('utilisateurs');
    Route::get('/utilisateurs/create', [SuperAdminController::class, 'create'])->name('utilisateurs.create');
    Route::post('/utilisateurs', [SuperAdminController::class, 'store'])->name('utilisateurs.store');
    Route::get('/utilisateurs/{id}/edit', [SuperAdminController::class, 'edit'])->name('utilisateurs.edit');
    Route::put('/utilisateurs/{id}', [SuperAdminController::class, 'update'])->name('utilisateurs.update');
    Route::delete('/utilisateurs/{id}', [SuperAdminController::class, 'destroy'])->name('utilisateurs.destroy');

    // Locations
    Route::get('/locations', [LocationController::class, 'gestionLocalite'])->name('locations.gestion-localite');

    // Materials
    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.gestion-material');
    Route::get('/materials/computers', [MaterialController::class, 'computers'])->name('materials.computers');
    Route::get('/materials/printers', [MaterialController::class, 'printers'])->name('materials.printers');
    Route::get('/materials/hotspots', [MaterialController::class, 'hotspots'])->name('materials.hotspots');
    Route::get('/materials/ip-phones', [MaterialController::class, 'ipPhones'])->name('materials.ip-phones');
    Route::get('/materials/site/{site}/{type}', [MaterialController::class, 'siteMaterials'])->name('materials.site');
    Route::get('/materials/list/{type}', [MaterialController::class, 'list'])->name('superadmin.materials.list');
    // Reclamations
    Route::get('/reclamations/reclamations', [SuperAdminController::class, 'reclamations'])->name('superadmin.reclamations');   
     Route::get('/reclamations/addreclamation', [SuperAdminController::class, 'addreclamation'])->name('reclamations.addreclamation');
     Route::post('/superadmin/reclamations', [SuperAdminController::class, 'storeReclamation'])->name('storeReclamation');
     Route::get('/reclamations/{id}', [SuperAdminController::class, 'showReclamation'])->name('reclamations');
    Route::delete('/superadmin/reclamations/{id}', [SuperAdminController::class, 'destroyReclamation'])->name('superadmin.reclamations.destroy');
    // Reclamation Messaging
    Route::post('/reclamations/{reclamationId}/messages', [SuperAdminController::class, 'storeMessage'])->name('messages.store');

    // Messages
    Route::get('/messages', [SuperAdminController::class, 'messages'])->name('messages.index');
    Route::get('/messages/{id}/view', [SuperAdminController::class, 'viewMessage'])->name('messages.view');
    Route::get('/messages/{id}/resolve', [SuperAdminController::class, 'resolveMessage'])->name('messages.resolve');
    Route::delete('/messages/{id}', [SuperAdminController::class, 'deleteMessage'])->name('messages.delete');
    Route::post('/messages/{messageId}/mark-as-seen', [SuperAdminController::class, 'markAsSeen'])->name('messages.mark-as-seen');
    Route::get('/messages/unread-count', [SuperAdminController::class, 'getUnreadCount'])->name('messages.unread-count');

    // COM & CBR Quick Access
    Route::get('/com', [SuperAdminController::class, 'com'])->name('com');
    Route::get('/cbr', [SuperAdminController::class, 'cbr'])->name('cbr');
});


    // Admin Management
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/utilisateurs', [AdminController::class, 'utilisateurs'])->name('utilisateurs');
        Route::get('/gestion-material', [AdminController::class, 'gestionMaterial'])->name('gestion-material');
        Route::get('/gestion-localite', [AdminController::class, 'gestionLocalite'])->name('gestion-localite');
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    });

    // Leader Management
    Route::prefix('leader')->name('leader.')->group(function () {
        Route::get('/dashboard', [LeaderController::class, 'dashboard'])->name('dashboard');
        Route::get('/utilisateurs', [LeaderController::class, 'utilisateurs'])->name('utilisateurs');
        Route::get('/gestion-material', [LeaderController::class, 'gestionMaterial'])->name('gestion-material');
        Route::get('/gestion-localite', [LeaderController::class, 'gestionLocalite'])->name('gestion-localite');
        Route::get('/affectations', [AffectationController::class, 'index'])->name('affectations.index');
    });



    Route::middleware(['auth'])->prefix('superadmin/locations')->name('superadmin.locations.')->group(function () {
        // Location management routes
        Route::get('/gestion-localite', [LocationController::class, 'gestionLocalite'])->name('gestion-localite');
        Route::get('/create', [LocationController::class, 'create'])->name('create');
        Route::post('/store', [LocationController::class, 'store'])->name('store');
        Route::get('/{location}/edit-type', [LocationController::class, 'editType'])->name('edit-type');
        Route::put('/{location}/update-type', [LocationController::class, 'updateType'])->name('update-type');
        Route::delete('/{location}', [LocationController::class, 'destroy'])->name('destroy');
        
        // Room management routes
        Route::get('/{location}/rooms', [LocationController::class, 'rooms'])->name('rooms');
        Route::get('/{location}/addroom', [LocationController::class, 'addroom'])->name('addroom');
        Route::post('/{location}/rooms', [LocationController::class, 'storeRoom'])->name('storeRoom');
        Route::put('/{location}/rooms/{room}/update-type', [LocationController::class, 'updateRoomType'])->name('updateRoomType');
        Route::delete('/{location}/rooms/{room}', [LocationController::class, 'destroyRoom'])->name('destroyRoom');
        Route::get('{location}/addroom', [LocationController::class, 'addroom'])->name('addroom');
        Route::post('{location}/rooms', [LocationController::class, 'storeRoom'])->name('storeRoom');
        Route::get('/{location}/corridors', [LocationController::class, 'corridors'])->name('corridors');
    Route::get('/{location}/addcorridor', [LocationController::class, 'addcorridor'])->name('addcorridor');
    Route::post('/{location}/corridors', [LocationController::class, 'storeCorridor'])->name('storeCorridor');
    Route::delete('/{location}/corridors/{corridor}', [LocationController::class, 'destroyCorridor'])->name('destroyCorridor');
    Route::get('/{location}/rooms/{room}/materials', [LocationController::class, 'viewRoomMaterials'])
        ->name('rooms.materials');

    // View materials for a corridor
    Route::get('/{location}/corridors/{corridor}/materials', [LocationController::class, 'viewCorridorMaterials'])
        ->name('corridors.materials');
    });
Route::prefix('superadmin/materials')->group(function () {
    // Main dashboard
    Route::get('/', [\App\Http\Controllers\SuperAdmin\MaterialController::class, 'index'])
        ->name('superadmin.materials.index');
    

 
       // Site-specific materials
    Route::get('/sites/{site}/{type}', [\App\Http\Controllers\SuperAdmin\MaterialController::class, 'siteMaterials'])
        ->name('superadmin.materials.site');

    
    // CRUD operations
    Route::get('/{type}/create', [\App\Http\Controllers\SuperAdmin\MaterialController::class, 'create'])
        ->name('superadmin.materials.create');
    Route::post('/{type}', [\App\Http\Controllers\SuperAdmin\MaterialController::class, 'store'])
        ->name('superadmin.materials.store');
 
    Route::get('/{type}/{material}/edit', [\App\Http\Controllers\SuperAdmin\MaterialController::class, 'edit'])
        ->name('superadmin.materials.edit');
    Route::put('/{type}/{material}', [\App\Http\Controllers\SuperAdmin\MaterialController::class, 'update'])
        ->name('superadmin.materials.update');
    Route::delete('/{type}/{material}', [\App\Http\Controllers\SuperAdmin\MaterialController::class, 'destroy'])
        ->name('superadmin.materials.destroy');
        
});

Route::get('/superadmin/cbr', [SuperAdminController::class, 'cbr'])->name('superadmin.cbr');
Route::get('/superadmin/com', [SuperAdminController::class, 'com'])->name('superadmin.com');
Route::get('/superadmin/com', [SuperAdminController::class, 'showCom'])->name('superadmin.com');
Route::get('/superadmin/cbr', [SuperAdminController::class, 'showCbr'])->name('superadmin.cbr');
Route::post('/superadmin/reclamations/add-reclamation', [SuperAdminController::class, 'storeReclamation'])
    ->name('superadmin.add-reclamation');
    Route::delete('/superadmin/reclamations/{id}', [SuperAdminController::class, 'destroy'])->name('superadmin.reclamations.destroy');
    Route::get('/superadmin/reclamations', [SuperAdminController::class, 'showReclamations'])->name('superadmin.reclamations');
    Route::delete('/superadmin/reclamations/{id}', [SuperAdminController::class, 'destroyReclamation'])->name('superadmin.reclamations.destroy');

;
// Display the printer creation form
// IP Phone routes
// routes/web.php


Route::prefix('superadmin/materials')->group(function () {
    // Dashboard route
    Route::get('/', [MaterialController::class, 'index'])->name('superadmin.materials.index');
    
    // Material type routes
    Route::prefix('{type}')->group(function () {
        // List materials by type
        Route::get('/', [MaterialController::class, 'list'])->name('superadmin.materials.list');
        
        // Add material form
        Route::get('/add', [MaterialController::class, 'create'])->name('superadmin.materials.create');
        
        // Store new material
        Route::post('/store', [MaterialController::class, 'store'])->name('superadmin.materials.store');
        
        // Edit material form
        Route::get('/{id}/edit', [MaterialController::class, 'edit'])->name('superadmin.materials.edit');
        
        // Update material
        Route::put('/{id}/update', [MaterialController::class, 'update'])->name('superadmin.materials.update');
        
        // Delete material
        Route::delete('/{id}/delete', [MaterialController::class, 'destroy'])->name('superadmin.materials.destroy');
    });
    Route::get('superadmin/materials/{type}/add', [MaterialController::class, 'create'])->name('superadmin.materials.create');
    Route::get('superadmin/materials/{type}/{id}/delete', [MaterialController::class, 'destroy'])->name('superadmin.materials.destroy');
    Route::get('superadmin/materials/{type}/{id}/delete', [MaterialController::class, 'destroy'])->name('superadmin.materials.destroy');
    Route::delete('/superadmin/materials/{type}/{id}', [MaterialController::class, 'destroy'])->name('superadmin.materials.destroy');
// For the materials overview page
Route::get('/superadmin/materials', [MaterialController::class, 'index'])->name('superadmin.materials.index');


// For creating materials
Route::get('/superadmin/materials/{type}/create', [MaterialController::class, 'create'])->name('superadmin.materials.create');

// For storing materials
Route::post('/superadmin/materials/{type}', [MaterialController::class, 'store'])->name('superadmin.materials.store');

// For deleting materials
Route::delete('/superadmin/materials/{type}/{id}', [MaterialController::class, 'destroy'])->name('superadmin.materials.destroy');
Route::get('/superadmin/materials/', [MaterialController::class, 'index'])->name('superadmin.materials.index');

// For listing materials by type (this is the important one)
Route::get('/superadmin/materials/{type}', [MaterialController::class, 'list'])
    ->where('type', 'computers|printers|ip-phones|hotspots')
    ->name('superadmin.materials.list');
    Route::get('/superadmin/materials/{type}', [MaterialController::class, 'list'])->name('superadmin.materials.list');
    // AJAX routes for dynamic dropdowns
    Route::get('/get-locations/{siteId}', [MaterialController::class, 'getLocationsBySite'])->name('superadmin.materials.getLocations');
    Route::get('/get-rooms/{locationId}', [MaterialController::class, 'getRoomsByLocation'])->name('superadmin.materials.getRooms');
    Route::get('/get-corridors/{locationId}', [MaterialController::class, 'getCorridorsByLocation'])->name('superadmin.materials.getCorridors');
});
// Store the new printer
Route::get('/superadmin/cbr', [BrancheController::class, 'carburantSites'])->name('superadmin.cbr');

    Route::post('/superadmin/reclamations/store', [SuperAdminController::class, 'storeReclamation'])->name('storeReclamation');
   // Show reclamation details
Route::get('/superadmin/reclamations/{id}', [SuperAdminController::class, 'showReclamation'])->name('superadmin.reclamations.show');

// Update reclamation status
Route::patch('/superadmin/reclamations/{id}/status/{status}', [SuperAdminController::class, 'updateStatus'])
->name('superadmin.reclamations.update-status');

// Store message
Route::post('/superadmin/reclamations/{reclamationId}/messages', [SuperAdminController::class, 'storeMessage'])
->name('superadmin.reclamations.store-message');
Route::patch('/superadmin/reclamations/{id}/status/{status}', [SuperAdminController::class, 'updateStatus'])
    ->name('superadmin.reclamations.update-status');

Route::post('/superadmin/reclamations/{reclamationId}/messages', [SuperAdminController::class, 'storeMessage'])
    ->name('superadmin.reclamations.store-message');
   
    // Update the superadmin dashboard route
Route::get('/superadmin/dashboard', [SuperAdminDashboardController::class, 'superadmindashboard'])
->name('superadmin.dashboard');

// Message routes
Route::prefix('superadmin/messages')->group(function () {
    Route::get('/unread-count', [SuperAdminController::class, 'getUnreadCount'])->name('messages.unread-count');
    Route::post('/mark-as-seen', [SuperAdminController::class, 'markAsSeen'])->name('messages.mark-as-seen');
});
Route::post('/locations/{location}/rooms/{room}/update-type', [LocationController::class, 'updateRoomType'])
    ->name('superadmin.locations.updateRoomType');



    require __DIR__.'/auth.php';