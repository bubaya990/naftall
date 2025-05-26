
@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="relative">
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.webp'); filter: blur(6px);"></div>
   
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        <!-- Materials Section with green gradient -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 backdrop-blur-sm rounded-xl shadow-lg p-4 border-l-4 border-green-500">
            
       <!-- Update the header actions section in materials.blade.php -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div class="animate-slideInLeft">
        <h1 class="text-2xl md:text-4xl font-extrabold text-gray-800">
            Matériels dans {{ $entityType === 'room' ? 'la salle' : 'le couloir' }}: {{ $entity->name }}
        </h1>
        <p class="text-gray-600 mt-2">{{ $location->site->name }} - {{ $location->name }}</p>
    </div>
    
    <div class="flex items-center gap-4">
        <a href="#" onclick="window.history.back(); return false;"
   class="text-green-700 hover:text-green-900 transition-colors duration-200 bg-green-100 hover:bg-green-200 px-4 py-2 rounded-lg font-medium border border-green-200">
    <i class="fas fa-arrow-left mr-2"></i> Retour
</a>

        @if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
        <a href="{{ route('locations.addMaterial', ['location' => $location->id, 'entityType' => $entityType, 'entity' => $entity->id]) }}" 
           class="btn btn-primary transform hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl">
            <i class="fas fa-plus-circle mr-2"></i> Ajouter
        </a>
        @endif
    </div>
</div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg border-l-4 border-green-500">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg border-l-4 border-red-500">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Materials Table -->
            <div class="overflow-x-auto w-full bg-white rounded-xl shadow-lg border-2 border-gray-200">
                <table class="w-full border-collapse">
                    <!-- Search headers row -->
                    <thead class="bg-gradient-to-r from-green-50 to-green-100">
                        <tr>
                            <!-- Inventory Number Search -->
                            <th class="px-2 py-2 border-b-2 border-green-200">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-barcode text-gray-600 text-xs"></i>
                                    </div>
                                    <input type="text" 
                                           class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-gray-300 rounded-full bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent font-medium text-gray-800" 
                                           placeholder="N° Inventaire" 
                                           id="search-inventory"
                                           data-column="0" />
                                </div>
                            </th>
                            
                            <!-- Serial Number Search -->
                            <th class="px-2 py-2 border-b-2 border-green-200">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-hashtag text-gray-600 text-xs"></i>
                                    </div>
                                    <input type="text" 
                                           class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-gray-300 rounded-full bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent font-medium text-gray-800" 
                                           placeholder="N° Série" 
                                           id="search-serial"
                                           data-column="1" />
                                </div>
                            </th>
                            
                            <!-- State Search -->
                            <th class="px-2 py-2 border-b-2 border-green-200">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-check-circle text-gray-600 text-xs"></i>
                                    </div>
                                    <select class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-gray-300 rounded-full bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent font-medium text-gray-800 appearance-none"
                                            id="search-state"
                                            data-column="2">
                                        <option value="">État</option>
                                        <option value="bon">Bon</option>
                                        <option value="défectueux">Défectueux</option>
                                        <option value="hors service">Hors service</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-600 text-xs"></i>
                                    </div>
                                </div>
                            </th>
                            
                            <!-- Type Search -->
                            <th class="px-2 py-2 border-b-2 border-green-200">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-tag text-gray-600 text-xs"></i>
                                    </div>
                                    <select class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-gray-300 rounded-full bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent font-medium text-gray-800 appearance-none"
                                            id="search-type"
                                            data-column="3">
                                        <option value="">Type</option>
                                        <option value="ordinateur">Ordinateur</option>
                                        <option value="imprimante">Imprimante</option>
                                        <option value="téléphone ip">Téléphone IP</option>
                                        <option value="hotspot">Hotspot</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-600 text-xs"></i>
                                    </div>
                                </div>
                            </th>
                            
                            <!-- Actions header (empty) -->
                            @if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
                            <th class="px-4 py-3 text-right text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b-2 border-green-200"></th>
                            @endif
                        </tr>
                    </thead>
                    
                    <!-- Column headers -->
                    <thead class="bg-gradient-to-r from-green-100 to-green-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b-2 border-green-200">N° Inventaire</th>
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b-2 border-green-200">N° Série</th>
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b-2 border-green-200">État</th>
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b-2 border-green-200">Type</th>
                            @if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
                            <th class="px-4 py-3 text-right text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b-2 border-green-200">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-gray-200" id="materialsTable">
                        @if($materials->isEmpty())
                            <tr>
                                <td colspan="10" class="px-4 py-6 text-center text-lg text-gray-600">
                                    Aucun matériel trouvé dans cette {{ $entityType === 'room' ? 'salle' : 'couloir' }}.
                                </td>
                            </tr>
                        @else
                            @foreach($materials as $material)
                      @php
                                $materialType = strtolower(class_basename($material->materialable_type));
                                $typeName = match($materialType) {
                                    'computer' => 'ordinateur',
                                    'printer' => 'imprimante',
                                    'ipphone' => 'téléphone ip',
                                    'hotspot' => 'hotspot',
                                    default => 'inconnu',
                                };
                            @endphp
                            <tr class="hover:bg-green-50/50">
                                <td class="px-4 py-3 text-gray-800 border-b border-gray-200" data-label="N° Inventaire">{{ $material->inventory_number }}</td>
                                <td class="px-4 py-3 text-gray-800 border-b border-gray-200" data-label="N° Série">{{ $material->serial_number }}</td>
                                <td class="px-4 py-3 border-b border-gray-200" data-label="État">
                                    @php
                                        $stateColors = [
                                            'bon' => 'bg-green-600 text-white',
                                            'défectueux' => 'bg-yellow-600 text-white',
                                            'hors service' => 'bg-red-600 text-white',
                                        ];
                                        $stateClass = $stateColors[$material->state] ?? 'bg-gray-600 text-white';
                                    @endphp
                                    <span class="px-3 py-1.5 text-xs md:text-sm font-bold rounded-full shadow-sm {{ $stateClass }}">
                                        {{ ucfirst($material->state) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-800 border-b border-gray-200" data-label="Type">
                                    {{ ucfirst($typeName) }}
                                </td>
                                
                                @if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
                                <td class="px-4 py-3 text-right space-x-2 md:space-x-3 border-b border-gray-200" data-label="Actions">
                                
                                                                                      @php
    $materialType = strtolower(class_basename($material->materialable_type));
    // Convert to the correct format used in routes
    $materialType = match($materialType) {
        'computer' => 'computers',
        'printer' => 'printers',
        'ipphone' => 'ip-phones',
        'hotspot' => 'hotspots',
        default => $materialType,
    };
@endphp
        
                                <!-- Edit Button -->
                                  <a href="{{ route('superadmin.materials.edit', ['type' => $materialType, 'id' => $material->id, 'source' => 'view']) }}"
   class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg font-bold border border-blue-200">
   <i class="fas fa-edit mr-1"></i>
   <span class="hidden md:inline">Modifier</span>
</a>
                                    
                                    <!-- Delete Button -->
                                    <button onclick="openDeleteModal('{{ $materialType }}', '{{ $material->id }}', '{{ $material->inventory_number }}')" 
                                            class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg font-bold border border-red-200">
                                        <i class="fas fa-trash-alt mr-1"></i>
                                        <span class="hidden md:inline">Supprimer</span>
                                    </button>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md border-2 border-green-200">
        <h3 class="text-xl font-bold text-red-600 mb-4">Confirmer la suppression</h3>
        <p class="text-gray-700 mb-2">Êtes-vous sûr de vouloir supprimer le matériel:</p>
        <p class="text-gray-900 font-semibold mb-1" id="material-to-delete-number"></p>
        <p class="text-red-500 text-sm mb-6">Attention: Cette action est irréversible.</p>
        
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="material_type" id="delete-material-type">
            <input type="hidden" name="material_id" id="delete-material-id">
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Annuler
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Confirmer la suppression
                </button>
            </div>
        </form>
    </div>
</div>
@endif



<style>
/* Green color scheme matching locations view */
.from-green-50 { --tw-gradient-from: #f0fdf4; }
.to-green-100 { --tw-gradient-to: #dcfce7; }
.from-green-100 { --tw-gradient-from: #dcfce7; }
.to-green-200 { --tw-gradient-to: #bbf7d0; }
.bg-green-50 { background-color: #f0fdf4; }
.bg-green-100 { background-color: #dcfce7; }
.bg-green-200 { background-color: #bbf7d0; }
.border-green-200 { border-color: #bbf7d0; }
.border-green-500 { border-color: #22c55e; }
.text-green-700 { color: #15803d; }

/* Action button colors */
.bg-green-500 { background-color: #22c55e; }
.bg-green-600 { background-color: #16a34a; }
.hover\:from-green-600:hover { --tw-gradient-from: #16a34a; }
.hover\:to-green-700:hover { --tw-gradient-to: #15803d; }

/* Modal styles */
.hidden { display: none !important; }
#deleteModal { 
    transition: opacity 0.3s ease;
    z-index: 9999;
}

/* Table styles */
table {
    border-collapse: separate;
    border-spacing: 0;
}

th, td {
    border-bottom: 1px solid #e5e7eb;
}

/* Table responsive styles */
@media (max-width: 768px) {
    td::before {
        content: attr(data-label);
        font-weight: bold;
        display: inline-block;
        width: 120px;
        color: #4b5563;
    }
}

/* Hover effects */
tr:hover {
    background-color: rgba(220, 252, 231, 0.5) !important;
}

/* Button transitions */
button, a {
    transition: all 0.2s ease-in-out;
}

/* Success/error message borders */
.border-green-500 { border-color: #22c55e; }
.border-red-500 { border-color: #ef4444; }
</style>


<script>
// Store the initial data from PHP
const sitesData = @json($sites);
const initialLocationId = {{ $material->room ? $material->room->location_id : ($material->corridor ? $material->corridor->location_id : 'null') }};
const initialRoomId = {{ $material->room_id ?? 'null' }};
const initialCorridorId = {{ $material->corridor_id ?? 'null' }};

// Function to populate locations dropdown based on selected site
function populateLocations(siteId) {
    const locationSelect = document.getElementById('locationSelect');
    locationSelect.innerHTML = '';
    
    // Find the selected site
    const selectedSite = sitesData.find(site => site.id == siteId);
    if (!selectedSite) return;
    
    // Add default option
    const defaultOption = new Option('Sélectionner un emplacement', '');
    locationSelect.add(defaultOption);
    
    // Add location options
    selectedSite.locations.forEach(location => {
        const option = new Option(location.type, location.id);
        locationSelect.add(option);
    });
    
    // If this is the initial load and we have a location ID, select it
    if (initialLocationId && selectedSite.locations.some(l => l.id == initialLocationId)) {
        locationSelect.value = initialLocationId;
    }
    
    // Trigger change event to populate rooms/corridors
    locationSelect.dispatchEvent(new Event('change'));
}

// Function to populate rooms or corridors based on selected location
function populateRoomsOrCorridors(locationId, locationType) {
    const selectedSite = sitesData.find(site => 
        site.locations.some(location => location.id == locationId)
    );
    
    if (!selectedSite) return;
    
    const selectedLocation = selectedSite.locations.find(l => l.id == locationId);
    if (!selectedLocation) return;
    
    if (locationType === 'room') {
        const roomSelect = document.getElementById('roomSelect');
        roomSelect.innerHTML = '';
        
        // Add default option
        const defaultOption = new Option('Sélectionner une salle', '');
        roomSelect.add(defaultOption);
        
        // Add room options
        selectedLocation.rooms.forEach(room => {
            const option = new Option(`${room.name} (${room.code})`, room.id);
            roomSelect.add(option);
        });
        
        // Select initial room if available
        if (initialRoomId && selectedLocation.rooms.some(r => r.id == initialRoomId)) {
            roomSelect.value = initialRoomId;
        }
    } else {
        const corridorSelect = document.getElementById('corridorSelect');
        corridorSelect.innerHTML = '';
        
        // Add default option
        const defaultOption = new Option('Sélectionner un couloir', '');
        corridorSelect.add(defaultOption);
        
        // Add corridor options
        selectedLocation.corridors.forEach(corridor => {
            const option = new Option(`Couloir ${corridor.id}`, corridor.id);
            corridorSelect.add(option);
        });
        
        // Select initial corridor if available
        if (initialCorridorId && selectedLocation.corridors.some(c => c.id == initialCorridorId)) {
            corridorSelect.value = initialCorridorId;
        }
    }
}

// Toggle between room and corridor fields
function toggleLocationType() {
    const roomRadio = document.querySelector('input[name="location_type"][value="room"]');
    document.getElementById('roomField').classList.toggle('hidden', !roomRadio.checked);
    document.getElementById('corridorField').classList.toggle('hidden', roomRadio.checked);
    
    // Trigger change to load appropriate rooms/corridors
    const locationSelect = document.getElementById('locationSelect');
    if (locationSelect.value) {
        locationSelect.dispatchEvent(new Event('change'));
    }
}

// Initialize the form when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Set up event listeners
    document.getElementById('siteSelect').addEventListener('change', function() {
        populateLocations(this.value);
    });
    
    document.getElementById('locationSelect').addEventListener('change', function() {
        const locationType = document.querySelector('input[name="location_type"]:checked').value;
        populateRoomsOrCorridors(this.value, locationType);
    });
    
    document.querySelectorAll('input[name="location_type"]').forEach(radio => {
        radio.addEventListener('change', toggleLocationType);
    });
    
    // Initialize the form
    const initialSiteId = {{ $material->room ? $material->room->location->site_id : ($material->corridor ? $material->corridor->location->site_id : 'null') }};
    if (initialSiteId) {
        document.getElementById('siteSelect').value = initialSiteId;
        populateLocations(initialSiteId);
    }
    
    // Set initial location type
    toggleLocationType();
});

// Handle form submission with modern fetch API
document.getElementById('editForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Show loading state
    submitButton.disabled = true;
    submitButton.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Enregistrement...
    `;
    
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw errorData;
        }

        const data = await response.json();
        
        if (data.success) {
            // Show success notification
            showNotification('Modifications enregistrées avec succès!', 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            throw new Error(data.message || 'Une erreur est survenue');
        }
    } catch (error) {
        let errorMessage = 'Une erreur est survenue';
        if (error.errors) {
            errorMessage = Object.values(error.errors).join('<br>');
        } else if (error.message) {
            errorMessage = error.message;
        }
        
        showNotification(errorMessage, 'error');
    } finally {
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    }
});

// Notification function
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white font-medium flex items-center ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'} mr-2"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('opacity-0', 'transition-opacity', 'duration-300');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}
</script>
@endsection