@extends('layouts.app')

@section('content')
<!-- CSRF Token Meta Tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Confirmation Modal -->
<div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-red-600 mb-4">Confirmer la suppression</h3>
        <p class="text-gray-700 mb-6">Êtes-vous sûr de vouloir supprimer ce matériel? Cette action est irréversible.</p>
        <div class="flex justify-end space-x-4">
            <button onclick="hideModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">Annuler</button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Supprimer</button>
            </form>
        </div>
    </div>
</div>

<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>
    
    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-6 pb-16">
        <!-- Dashboard content with glassmorphism effect -->
        <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-8 max-w-7xl mx-auto mt-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-900">
                        Matériels dans {{ $entityType === 'room' ? 'la salle' : 'le couloir' }}: {{ $entity->name }}
                    </h1>
                    <p class="text-gray-600 mt-1">Localité: {{ $location->name }}</p>
                </div>
                
                <!-- Back button -->
                <div>
                    <a href="{{ $entityType === 'room' ? route('superadmin.locations.rooms', $location) : route('superadmin.locations.corridors', $location) }}" 
                       class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                    <a href="{{ route('locations.addMaterial', ['location' => $location->id, 'entityType' => $entityType, 'entity' => $entity->id]) }}" 
                       class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 inline-flex items-center ml-4">
                        <i class="fas fa-plus mr-2"></i> Ajouter Matériel
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Materials Table -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg overflow-hidden">
                @if($materials->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-blue-50/50">
                            <tr>
                                <th class="px-6 py-3 text-blue-900 font-medium">Numéro d'inventaire</th>
                                <th class="px-6 py-3 text-blue-900 font-medium">Numéro de série</th>
                                <th class="px-6 py-3 text-blue-900 font-medium">Type</th>
                                <th class="px-6 py-3 text-blue-900 font-medium">État</th>
                                <th class="px-6 py-3 text-blue-900 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/50">
                            @foreach($materials as $material)
                            @php
                                // Determine the material type based on your controller expectations
                                $materialType = strtolower(class_basename($material->materialable_type));
                            @endphp
                            <tr class="hover:bg-blue-50/30 transition-colors duration-200">
                                <td class="px-6 py-4 font-medium">{{ $material->inventory_number }}</td>
                                <td class="px-6 py-4">{{ $material->serial_number }}</td>
                                <td class="px-6 py-4">
                                    @switch($materialType)
                                        @case('computer')
                                            Ordinateur
                                            @break
                                        @case('printer')
                                            Imprimante
                                            @break
                                        @case('ipphone')
                                            Téléphone IP
                                            @break
                                        @case('hotspot')
                                            Hotspot
                                            @break
                                        @default
                                            Inconnu
                                    @endswitch
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium 
                                        @if($material->state === 'bon') bg-green-100 text-green-800
                                        @elseif($material->state === 'défectueux') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $material->state }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('superadmin.materials.edit', ['type' => $materialType, 'id' => $material->id]) }}" 
                                           class="text-blue-600 hover:text-blue-800 transition p-2 rounded-full hover:bg-blue-100">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="showDeleteModal('{{ route('superadmin.materials.destroy', ['type' => $materialType, 'id' => $material->id]) }}')" 
                                                class="text-red-600 hover:text-red-800 transition p-2 rounded-full hover:bg-red-100">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-500">Aucun matériel trouvé</h3>
                    <p class="text-gray-400 mt-1">Cette {{ $entityType === 'room' ? 'salle' : 'couloir' }} ne contient aucun matériel</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function showDeleteModal(url) {
        const modal = document.getElementById('confirmationModal');
        const form = document.getElementById('deleteForm');
        
        form.action = url;
        modal.classList.remove('hidden');
    }
    
    function hideModal() {
        const modal = document.getElementById('confirmationModal');
        modal.classList.add('hidden');
    }
</script>
@endsection