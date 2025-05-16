@extends('layouts.app')

@section('content')
<!-- CSRF Token Meta Tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>
    
    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        <!-- Corridors content with gradient background -->
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 backdrop-blur-sm rounded-xl shadow-lg p-4 border-l-4 border-yellow-500 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl">
            <!-- Header with back button -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="animate-slideInLeft">
                    <h1 class="text-2xl md:text-4xl font-extrabold text-gray-800">Gestion des Couloirs</h1>
                    <p class="text-gray-600 mt-2">{{ $location->site->name }} - {{ $location->name ?? 'Localité' }}</p>
                </div>
                <a href="{{ route('superadmin.locations.gestion-localite') }}" 
                   class="text-yellow-700 hover:text-yellow-900 transition-colors duration-200 bg-yellow-100 hover:bg-yellow-200 px-4 py-2 rounded-lg font-medium border border-yellow-200">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>

            <!-- Add corridor button (Only for SuperAdmin) -->
            @auth
                @if(auth()->user()->role === 'superadmin')
                    <div class="mb-8 animate-slideInRight">
                        <a href="{{ route('superadmin.locations.addcorridor', $location->id) }}" 
                           class="btn btn-primary transform hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl">
                            <i class="fas fa-plus-circle mr-2"></i> Ajouter un couloir
                        </a>
                    </div>
                @endif
            @endauth

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
            <div class="bg-white backdrop-blur-sm rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl border border-gray-200 animate-fadeIn">
                @if($corridors->count())
                    <div class="overflow-x-auto w-full">
                        <table class="w-full border-collapse bg-white">
                            <thead class="bg-gradient-to-r from-yellow-50 to-yellow-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b border-yellow-200">ID</th>
                                    <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b border-yellow-200">Nom</th>
                                    <th class="px-4 py-3 text-right text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b border-yellow-200">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($corridors as $corridor)
                                    <tr class="hover:bg-yellow-50/30 transition-colors duration-200">
                                        <td class="px-4 py-3 text-gray-800 border-b border-gray-200">#{{ $corridor->id }}</td>
                                        <td class="px-4 py-3 text-gray-800 border-b border-gray-200">{{ $corridor->name ?? 'Non spécifié' }}</td>
                                        <td class="px-4 py-3 text-right space-x-2 md:space-x-3 border-b border-gray-200">
                                            <a href="{{ route('superadmin.locations.corridors.materials', ['location' => $location->id, 'corridor' => $corridor->id]) }}" 
                                               class="text-blue-600 hover:text-blue-800 transform hover:scale-110 transition duration-200 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg font-bold border border-blue-200">
                                                <i class="fas fa-eye mr-1"></i>
                                                <span class="hidden md:inline">Matériel</span>
                                            </a>
                                            
                                            @auth
                                                @if(auth()->user()->role === 'superadmin')
                                                    <button onclick="openDeleteModal('{{ $corridor->id }}', '{{ $corridor->name }}')"
                                                            class="text-red-600 hover:text-red-800 transform hover:scale-110 transition duration-200 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg font-bold border border-red-200">
                                                        <i class="fas fa-trash-alt mr-1"></i>
                                                        <span class="hidden md:inline">Supprimer</span>
                                                    </button>
                                                @endif
                                            @endauth
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
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md border-2 border-yellow-200">
        <h3 class="text-xl font-bold text-red-600 mb-4">Confirmer la suppression</h3>
        <p class="text-gray-700 mb-2">Êtes-vous sûr de vouloir supprimer le couloir :</p>
        <p class="text-gray-900 font-semibold mb-1" id="corridor-to-delete-name"></p>
        <p class="text-red-500 text-sm mb-6">Attention: Cette action supprimera également tous les matériels associés.</p>
        
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="corridor_id" id="delete-corridor-id">
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-yellow-500">
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
document.addEventListener('DOMContentLoaded', function() {
    // Delete Modal Functions
    window.openDeleteModal = function(corridorId, corridorName) {
        const modal = document.getElementById('deleteModal');
        if (!modal) {
            console.error('Delete modal element not found');
            return;
        }
        
        document.getElementById('delete-corridor-id').value = corridorId;
        document.getElementById('corridor-to-delete-name').textContent = corridorName;
        document.getElementById('deleteForm').action = `/superadmin/locations/{{ $location->id }}/corridors/${corridorId}`;
        modal.classList.remove('hidden');
    };

    window.closeDeleteModal = function() {
        const modal = document.getElementById('deleteModal');
        if (modal) modal.classList.add('hidden');
    };
});
</script>

<style>
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
@keyframes slideInRight { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }

.animate-fadeIn { animation: fadeIn 0.6s ease-out forwards; }
.animate-fadeInUp { animation: fadeInUp 0.6s ease-out forwards; }
.animate-slideInLeft { animation: slideInLeft 0.6s ease-out forwards; }
.animate-slideInRight { animation: slideInRight 0.6s ease-out forwards; }

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
    background-color: rgba(254, 249, 195, 0.3) !important;
    transition: background-color 0.2s ease;
}

/* Button transitions */
button, a {
    transition: all 0.2s ease-in-out;
}

/* Yellow color accents */
.from-yellow-50 { --tw-gradient-from: #fefce8; }
.to-yellow-100 { --tw-gradient-to: #fef9c3; }
.from-yellow-100 { --tw-gradient-from: #fef9c3; }
.to-yellow-200 { --tw-gradient-to: #fef08a; }
.bg-yellow-100 { background-color: #fef9c3; }
.bg-yellow-200 { background-color: #fef08a; }
.border-yellow-200 { border-color: #fef08a; }
.text-yellow-700 { color: #a16207; }
.bg-yellow-50 { background-color: #fefce8; }

/* Action button colors */
.bg-yellow-500 { background-color: #eab308; }
.bg-yellow-600 { background-color: #ca8a04; }
.hover\:from-yellow-600:hover { --tw-gradient-from: #ca8a04; }
.hover\:to-yellow-700:hover { --tw-gradient-to: #a16207; }
</style>

@endsection