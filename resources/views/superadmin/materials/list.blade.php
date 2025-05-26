@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="relative">
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.webp'); filter: blur(6px);"></div>
   
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        <!-- Materials Section -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 backdrop-blur-sm rounded-xl shadow-lg p-4 border-l-4 border-green-500">
            
            <!-- Header with actions -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl md:text-4xl font-extrabold text-gray-800">
                        @if($type == 'computers') Ordinateurs
                        @elseif($type == 'printers') Imprimantes
                        @elseif($type == 'ip-phones') Téléphones IP
                        @elseif($type == 'hotspots') Hotspots
                        @endif
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm md:text-base">Liste complète des équipements</p>
                </div>
                
                <div>
                    <a href="{{ route('superadmin.materials.index') }}" 
                      class="btn btn-primary transform hover:scale-105 transition-transform duration-300 ">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>

                    @if(auth()->user()->role === 'superadmin' || (auth()->user()->role === 'admin' && auth()->user()->site_id))
                    <a href="{{ route('superadmin.materials.create', ['type' => $type]) }}" 
                       class="btn btn-primary transform hover:scale-105 transition-transform duration-300">
                       <i class="fas fa-plus-circle mr-2"></i>Ajouter
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
                            
                            <!-- Site Search -->
                            <th class="px-2 py-2 border-b-2 border-green-200">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-building text-gray-600 text-xs"></i>
                                    </div>
                                    <input type="text" 
                                           class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-gray-300 rounded-full bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent font-medium text-gray-800" 
                                           placeholder="Site" 
                                           id="search-site"
                                           data-column="3" />
                                </div>
                            </th>
                            
                            <!-- Location Search -->
                            <th class="px-2 py-2 border-b-2 border-green-200">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-gray-600 text-xs"></i>
                                    </div>
                                    <input type="text" 
                                           class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-gray-300 rounded-full bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent font-medium text-gray-800" 
                                           placeholder="Emplacement" 
                                           id="search-location"
                                           data-column="4" />
                                </div>
                            </th>
                            
                            <!-- Type-specific search -->
                            @if($type == 'computers')
                            <th class="px-2 py-2 border-b-2 border-green-200">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-desktop text-gray-600 text-xs"></i>
                                    </div>
                                    <input type="text" 
                                           class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-gray-300 rounded-full bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent font-medium text-gray-800" 
                                           placeholder="Marque" 
                                           id="search-brand"
                                           data-column="5" />
                                </div>
                            </th>
                            @endif
                            
                            <!-- Actions header (empty) -->
                            @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin')
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
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b-2 border-green-200">Site</th>
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b-2 border-green-200">Emplacement</th>
                            @if($type == 'computers')
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b-2 border-green-200">Marque</th>
                            @endif
                            @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin')
                            <th class="px-4 py-3 text-right text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b-2 border-green-200">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-gray-200" id="materialsTable">
                        @if ($materials->isEmpty())
                            <tr>
                                <td colspan="10" class="px-4 py-6 text-center text-lg text-gray-600">
                                    Aucun matériel trouvé pour ce type.
                                </td>
                            </tr>
                        @else
                            @foreach($materials as $index => $material)
                                @php
                                    // Get the site ID for this material
                                    $materialSiteId = $material->room ? $material->room->location->site_id : ($material->corridor ? $material->corridor->location->site_id : null);
                                    
                                    // Check if admin can see this material (only their site's materials)
                                    $canView = auth()->user()->role === 'superadmin' || 
                                              (auth()->user()->role === 'admin' && 
                                               auth()->user()->site_id && 
                                               $materialSiteId == auth()->user()->site_id);
                                @endphp
                                
                                @if($canView)
                                <tr class="hover:bg-green-50/50">
                                    <td class="px-4 py-3 text-gray-800 border-b border-gray-200">{{ $material->inventory_number }}</td>
                                    <td class="px-4 py-3 text-gray-800 border-b border-gray-200">{{ $material->serial_number }}</td>
                                    <td class="px-4 py-3 border-b border-gray-200">
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
                                    <td class="px-4 py-3 text-gray-800 border-b border-gray-200">
                                        {{ $material->room->location->site->name ?? $material->corridor->location->site->name ?? 'N/A' }}
                                    </td>
    <td class="px-4 py-3 text-gray-800 border-b border-gray-200">
    @if($material->room)
        <i class="fas fa-door-open text-green-600 mr-1"></i>
        {{ $material->room->location->site->name }} > 
        {{ $material->room->location->name }} > 
        Salle: {{ $material->room->name }} ({{ $material->room->code }})
    @elseif($material->corridor)
        <i class="fas fa-arrows-alt-h text-blue-600 mr-1"></i>
        {{ $material->corridor->location->site->name }} > 
        {{ $material->corridor->location->name }} > 
        Couloir: {{ $material->corridor->name }}
    @else
        <i class="fas fa-question-circle text-gray-400 mr-1"></i>
        N/A
    @endif
</td>
                                    
                                    @if($type == 'computers')
                                    <td class="px-4 py-3 text-gray-800 border-b border-gray-200">{{ $material->materialable->computer_brand }}</td>
                                    @endif
                                    
                                    @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin')
                                    <td class="px-4 py-3 text-right space-x-2 md:space-x-3 border-b border-gray-200">
                                        <!-- Edit Button -->
                                        <a href="{{ route('superadmin.materials.edit', ['type' => $type, 'id' => $material->id, 'source' => 'list']) }}"
                                           class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg font-bold border border-blue-200">
                                           <i class="fas fa-edit mr-1"></i>
                                           <span class="hidden md:inline">Modifier</span>
                                        </a>
                                        
                                        <!-- Delete Button -->
                                        <button onclick="openDeleteModal('{{ $type }}', '{{ $material->id }}', '{{ $material->inventory_number }}')" 
                                                class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg font-bold border border-red-200">
                                            <i class="fas fa-trash-alt mr-1"></i>
                                            <span class="hidden md:inline">Supprimer</span>
                                        </button>
                                    </td>
                                    @endif
                                </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin')
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

<script>
// Delete Modal Functions
function openDeleteModal(type, id, inventoryNumber) {
    const modal = document.getElementById('deleteModal');
    const typeInput = document.getElementById('delete-material-type');
    const idInput = document.getElementById('delete-material-id');
    const numberSpan = document.getElementById('material-to-delete-number');
    
    typeInput.value = type;
    idInput.value = id;
    numberSpan.textContent = inventoryNumber;
    
    // Set the form action
    document.getElementById('deleteForm').action = `/superadmin/materials/${type}/${id}`;
    
    modal.classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    // Get all search inputs
    const searchInputs = document.querySelectorAll('input[id^="search-"], select[id^="search-"]');
    const rows = document.querySelectorAll('#materialsTable tr');

    // Function to filter rows based on all search criteria
    function filterRows() {
        const filters = {};
        
        // Collect all filter values
        searchInputs.forEach(input => {
            const column = input.getAttribute('data-column');
            filters[column] = input.value.toLowerCase();
        });

        rows.forEach(row => {
            if (row.cells.length <= 1) return; // Skip the "no results" row
            
            let visible = true;
            const cells = row.cells;

            // Check each filter
            for (const [column, value] of Object.entries(filters)) {
                if (value === '') continue;
                
                const cell = cells[column];
                let cellText = cell.textContent.toLowerCase();
                
                // Special handling for state column (span content)
                if (column === '2') {
                    const span = cell.querySelector('span');
                    if (span) {
                        cellText = span.textContent.toLowerCase();
                    }
                }
                
                if (!cellText.includes(value)) {
                    visible = false;
                    break;
                }
            }

            // Apply visibility
            if (visible) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }

    // Add event listeners to all search inputs
    searchInputs.forEach(input => {
        input.addEventListener('input', filterRows);
    });

    // Responsive data labels
    const headers = document.querySelectorAll('thead th');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        cells.forEach((cell, index) => {
            if (index < headers.length) {
                cell.setAttribute('data-label', headers[index].textContent);
            }
        });
    });
});
</script>

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
@endsection