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
            <!-- Header with back button -->
            <div class="mb-8 flex justify-between items-center">
                <div class="animate-slideInLeft">
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-900">Gestion des couloirs</h1>
                    <p class="text-gray-600 mt-2">{{ $location->site->name }} - {{ $location->name ?? 'Localité' }}</p>
                </div>
                <a href="{{ route('superadmin.locations.gestion-localite') }}" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-1"></i> Retour
                </a>
            </div>

            <!-- Add corridor button -->
            <div class="mb-8 animate-slideInRight">
                <a href="{{ route('superadmin.locations.addcorridor', $location->id) }}" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 inline-flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i> Ajouter un couloir
                </a>
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

            <!-- Corridors list -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl animate-fadeIn">
                @if($corridors->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-green-50/50">
                                <tr>
                                    <th class="px-6 py-3 text-green-900 font-medium">ID</th>
                                    <th class="px-6 py-3 text-green-900 font-medium">Nom</th>
                                    <th class="px-6 py-3 text-green-900 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200/50">
                                @foreach($corridors as $corridor)
                                    <tr class="hover:bg-green-50/30 transition-colors duration-200">
                                        <td class="px-6 py-4">#{{ $corridor->id }}</td>
                                        <td class="px-6 py-4">{{ $corridor->name ?? 'Non spécifié' }}</td>
                                        <td class="px-6 py-4 space-x-3">
                                            <a href="{{ route('superadmin.locations.corridors.materials', ['location' => $location->id, 'corridor' => $corridor->id]) }}" 
                                               class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                                <i class="fas fa-eye mr-1"></i> Voir Matériel
                                            </a>
                                            <button onclick="openDeleteModal('{{ $corridor->id }}', '{{ $corridor->name }}')" 
                                                    class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                                <i class="fas fa-trash mr-1"></i> Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-route text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Pas de couloirs dans cette localité.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md">
        <h3 class="text-xl font-bold text-red-600 mb-4">Confirmer la suppression</h3>
        <p class="text-gray-700 mb-6">Êtes-vous sûr de vouloir supprimer le couloir: <span id="corridor-to-delete-name" class="font-semibold"></span>?</p>
        
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="corridor_id" id="delete-corridor-id">
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Annuler
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Supprimer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Delete Modal Functions
    function openDeleteModal(corridorId, corridorName) {
        const modal = document.getElementById('deleteModal');
        const corridorIdInput = document.getElementById('delete-corridor-id');
        const corridorNameSpan = document.getElementById('corridor-to-delete-name');
        
        corridorIdInput.value = corridorId;
        corridorNameSpan.textContent = corridorName;
        
        // Set the form action
        document.getElementById('deleteForm').action = `/superadmin/locations/{{ $location->id }}/corridors/${corridorId}`;
        
        modal.classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection