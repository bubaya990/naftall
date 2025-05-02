@extends('layouts.app')

@section('content')
<!-- CSRF Token Meta Tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>
    
    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-6 pb-16">
        <!-- Dashboard content with glassmorphism effect -->
        <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-8 max-w-7xl mx-auto mt-8 transition-all duration-500 transform hover:scale-[1.01]">
            <!-- Header section -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <!-- Title in top left -->
                    <div class="animate-slideInLeft">
                        <h1 class="text-2xl md:text-3xl font-bold text-blue-900">Gestion des localités</h1>
                    </div>
                    
                    <!-- Create button -->
                    <div class="animate-slideInRight w-full sm:w-auto">
                        <a href="{{ route('superadmin.locations.create') }}" class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 inline-flex items-center justify-center w-full sm:w-auto">
                            <i class="fas fa-plus-circle mr-2"></i> Nouvelle localité
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success/Error messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg animate-fadeIn">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg animate-fadeIn">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Sites and Locations -->
            <div class="space-y-6">
                @foreach($sites as $site)
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl animate-fadeIn">
                    <div class="p-4 border-b border-gray-200/50 bg-gradient-to-r from-blue-50 to-blue-100">
                        <h2 class="font-semibold text-blue-900 text-xl flex items-center">
                            <i class="fas fa-building mr-2 text-yellow-600"></i>
                            {{ $site->name }}
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        @if($site->locations->count())
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead class="bg-blue-50/50">
                                        <tr>
                                            <th class="px-6 py-3 text-blue-900 font-medium">Nom</th>
                                            <th class="px-6 py-3 text-blue-900 font-medium">Type</th>
                                            <th class="px-6 py-3 text-blue-900 font-medium">Etage</th>
                                            <th class="px-6 py-3 text-blue-900 font-medium">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200/50">
                                        @foreach($site->locations as $location)
                                            <tr class="hover:bg-blue-50/30 transition-colors duration-200">
                                                <td class="px-6 py-4">{{ $location->name ?? '—' }}</td>
                                                <td class="px-6 py-4">{{ $location->type }}</td>
                                                <td class="px-6 py-4">{{ $location->floor->floor_number ?? '—' }}</td>
                                                <td class="px-6 py-4">
                                                    <div class="flex justify-between items-center w-full">
                                                        <!-- Room button on left -->
                                                        <div class="flex-1">
                                                            <a href="{{ route('superadmin.locations.rooms', $location->id) }}" 
                                                               class="text-blue-600 hover:text-blue-800 transition-colors duration-200 inline-flex items-center">
                                                                <i class="fas fa-door-open mr-1"></i> Salle
                                                            </a>
                                                        </div>
                                                        
                                                        <!-- Corridor button centered -->
                                                        <div class="flex-1 text-center">
                                                            @if(in_array($location->type, ['Rez-de-chaussee', 'Étage']))
                                                            <a href="{{ route('superadmin.locations.corridors', $location->id) }}" 
                                                               class="text-green-600 hover:text-green-800 transition-colors duration-200 inline-flex items-center justify-center">
                                                                <i class="fas fa-route mr-1"></i> Couloir
                                                            </a>
                                                            @endif
                                                        </div>
                                                        
                                                        <!-- Delete button on right -->
                                                        <div class="flex-1 text-right">
                                                            <button onclick="openDeleteModal('{{ $location->id }}', '{{ $location->name }}')" 
                                                                    class="text-red-600 hover:text-red-900 inline-flex items-center justify-end">
                                                                <i class="fas fa-trash mr-1"></i> Supprimer
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-map-marker-alt text-4xl text-gray-300 mb-3"></i>
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

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md">
        <h3 class="text-xl font-bold text-red-600 mb-4">Confirmer la suppression</h3>
        <p class="text-gray-700 mb-6">Êtes-vous sûr de vouloir supprimer la localité: <span id="location-to-delete-name" class="font-semibold"></span>?</p>
        <p class="text-red-500 text-sm mb-6">Attention: Cette action supprimera également toutes les salles et couloirs associés.</p>
        
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="location_id" id="delete-location-id">
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
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

<script>
    // Delete Modal Functions
    function openDeleteModal(locationId, locationName) {
        const modal = document.getElementById('deleteModal');
        const locationIdInput = document.getElementById('delete-location-id');
        const locationNameSpan = document.getElementById('location-to-delete-name');
        
        locationIdInput.value = locationId;
        locationNameSpan.textContent = locationName;
        
        // Set the form action
        document.getElementById('deleteForm').action = `/superadmin/locations/${locationId}`;
        
        modal.classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection