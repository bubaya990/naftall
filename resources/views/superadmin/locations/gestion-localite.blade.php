@extends('layouts.app')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="relative">
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>
   
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        <!-- Locations Section with muted brown-yellow theme -->
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 backdrop-blur-sm rounded-xl shadow-lg p-4 border-l-4 border-amber-600">
            
            <!-- Header with actions -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl md:text-4xl font-extrabold text-gray-800">Gestion des Localités</h1>
                    <p class="text-gray-600 mt-1 text-sm md:text-base">Liste complète des localités par site</p>
                </div>
                
                @if(auth()->user()->role === 'superadmin')
                <div class="flex space-x-2">
                    <a href="{{ route('superadmin.locations.create') }}" 
                       class="btn btn-primary transform hover:scale-105 transition-transform duration-300 bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg shadow-lg hover:shadow-xl flex items-center">
                        <i class="fas fa-plus-circle mr-2"></i>Nouvelle localité
                    </a>
                </div>
                @endif
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg border-l-4 border-green-600">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg border-l-4 border-red-600">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Sites List -->
            <div class="space-y-6">
                @foreach($sites as $site)
                    @php
                        if(auth()->user()->role === 'admin' && $site->id != auth()->user()->site_id) {
                            continue;
                        }
                    @endphp
                    
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 border-amber-100">
                        <!-- Site Name Header -->
                        <div class="p-4 border-b border-amber-200 bg-gradient-to-r from-amber-100 to-amber-200">
                            <h2 class="font-semibold text-gray-800 text-xl flex items-center">
                                <i class="fas fa-building mr-2 text-amber-700"></i>
                                {{ $site->name }}
                            </h2>
                        </div>
                       
                        <div class="p-6">
                            @if($site->locations->count())
                                <div class="overflow-x-auto w-full">
                                    <table class="w-full border-collapse bg-white">
                                        <!-- Table Header -->
                                        <thead class="bg-gradient-to-r from-amber-100 to-amber-200">
                                            <tr class="text-gray-700 uppercase">
                                                <th class="px-4 py-3 text-left text-sm md:text-base font-bold tracking-wider border-b-2 border-amber-300">Nom</th>
                                                <th class="px-4 py-3 text-left text-sm md:text-base font-bold tracking-wider border-b-2 border-amber-300">Type</th>
                                                <th class="px-4 py-3 text-left text-sm md:text-base font-bold tracking-wider border-b-2 border-amber-300">Etage</th>
                                                <th class="px-4 py-3 text-right text-sm md:text-base font-bold tracking-wider border-b-2 border-amber-300">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-amber-100">
                                            @foreach($site->locations as $location)
                                                <tr class="hover:bg-amber-50/40" data-location-id="{{ $location->id }}">
                                                    <td class="px-4 py-3 text-gray-800 border-b border-amber-100">{{ $location->name ?? '—' }}</td>
                                                    <td class="px-4 py-3 border-b border-amber-100">
                                                        <span class="px-3 py-1.5 text-xs md:text-sm font-bold rounded-full shadow-sm bg-amber-100 text-amber-800 border border-amber-200">
                                                            {{ $location->type }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-gray-800 border-b border-amber-100">{{ $location->floor->floor_number ?? '—' }}</td>
                                                    <td class="px-4 py-3 text-right space-x-2 md:space-x-3 border-b border-amber-100">
                                                        <a href="{{ route('superadmin.locations.rooms', $location->id) }}"
                                                           class="inline-flex items-center text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg font-bold border border-blue-200 transition-all duration-200">
                                                            <i class="fas fa-door-open mr-1"></i>
                                                            <span class="whitespace-nowrap">Voir salles</span>
                                                        </a>
                                                        
                                                        @if(in_array($location->type, ['Rez-de-chaussee', 'Étage']))
                                                        <a href="{{ route('superadmin.locations.corridors', $location->id) }}"
                                                           class="inline-flex items-center text-green-600 hover:text-green-800 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg font-bold border border-green-200 transition-all duration-200">
                                                            <i class="fas fa-route mr-1"></i>
                                                            <span class="whitespace-nowrap">Voir couloirs</span>
                                                        </a>
                                                        @endif
                                                        
                                                        @if(auth()->user()->role === 'superadmin')
                                                        <button onclick="openDeleteModal('{{ $location->id }}', '{{ $location->name }}')"
                                                                class="inline-flex items-center text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg font-bold border border-red-200 transition-all duration-200">
                                                            <i class="fas fa-trash-alt mr-1"></i>
                                                            <span class="whitespace-nowrap">Supprimer</span>
                                                        </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-map-marker-alt text-4xl text-amber-400 mb-3"></i>
                                    <p class="text-gray-500">Pas de localité dans ce site.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->role === 'superadmin')
<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md border-2 border-amber-300">
        <h3 class="text-xl font-bold text-red-600 mb-4">Confirmer la suppression</h3>
        <p class="text-gray-700 mb-2">Êtes-vous sûr de vouloir supprimer la localité :</p>
        <p class="text-gray-900 font-semibold mb-1" id="location-to-delete-name"></p>
        <p class="text-red-500 text-sm mb-6">Attention: Cette action supprimera également toutes les salles et couloirs associés.</p>
       
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="location_id" id="delete-location-id">
           
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all duration-200">
                    Annuler
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200">
                    Confirmer la suppression
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete Modal Functions
    window.openDeleteModal = function(locationId, locationName) {
        const modal = document.getElementById('deleteModal');
        if (!modal) {
            console.error('Delete modal element not found');
            return;
        }
        
        document.getElementById('delete-location-id').value = locationId;
        document.getElementById('location-to-delete-name').textContent = locationName;
        document.getElementById('deleteForm').action = `/superadmin/locations/${locationId}`;
        modal.classList.remove('hidden');
    };

    window.closeDeleteModal = function() {
        const modal = document.getElementById('deleteModal');
        if (modal) modal.classList.add('hidden');
    };
});
</script>

<style>
/* Muted brown-yellow (amber) color scheme */
.from-amber-50 { --tw-gradient-from: #fffbeb; }
.to-amber-100 { --tw-gradient-to: #fef3c7; }
.from-amber-100 { --tw-gradient-from: #fef3c7; }
.to-amber-200 { --tw-gradient-to: #fde68a; }
.bg-amber-50 { background-color: #fffbeb; }
.bg-amber-100 { background-color: #fef3c7; }
.bg-amber-200 { background-color: #fde68a; }
.border-amber-100 { border-color: #fef3c7; }
.border-amber-200 { border-color: #fde68a; }
.border-amber-300 { border-color: #fcd34d; }
.border-amber-600 { border-color: #d97706; }
.text-amber-400 { color: #fbbf24; }
.text-amber-700 { color: #b45309; }
.text-amber-800 { color: #92400e; }

/* Action button colors */
.bg-amber-600 { background-color: #d97706; }
.bg-amber-700 { background-color: #b45309; }
.hover\:bg-amber-700:hover { background-color: #b45309; }

/* Table styles */
table {
    border-collapse: separate;
    border-spacing: 0;
}

th, td {
    border-bottom: 1px solid #fde68a;
}

/* Hover effects */
tr:hover {
    background-color: rgba(254, 243, 199, 0.4) !important;
}

/* Button transitions */
button, a {
    transition: all 0.2s ease-in-out;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .flex-col.md\:flex-row {
        flex-direction: column;
    }
    
    .space-x-2.md\:space-x-3 > * {
        margin-bottom: 0.5rem;
    }
    
    .inline-flex {
        display: inline-flex;
        width: 100%;
        justify-content: center;
    }
}

/* Success/error message borders */
.border-green-600 { border-color: #16a34a; }
.border-red-600 { border-color: #dc2626; }
</style>

@endsection