@extends('layouts.app')

@section('content')
<!-- CSRF Token Meta Tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.webp'); filter: blur(6px);"></div>
    
    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        <!-- Rooms content with gradient background -->
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 backdrop-blur-sm rounded-xl shadow-lg p-4 border-l-4 border-yellow-500 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl">
            <!-- Header with actions -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="animate-slideInLeft">
                    <h1 class="text-2xl md:text-4xl font-extrabold text-gray-800">Gestion des Salles</h1>
                    <p class="text-gray-600 mt-2">{{ $rooms->first()->location->site->name ?? 'N/A' }} - {{ $rooms->first()->location->name ?? 'N/A' }}</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('superadmin.locations.gestion-localite') }}" 
                       class="text-yellow-700 hover:text-yellow-900 transition-colors duration-200 bg-yellow-100 hover:bg-yellow-200 px-4 py-2 rounded-lg font-medium border border-yellow-200">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                    
                    @if(auth()->user()->role === 'superadmin')
                    <a href="{{ route('superadmin.locations.addroom', ['location' => $locationId]) }}" 
                       class="btn btn-primary transform hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl">
                        <i class="fas fa-plus-circle mr-2"></i>Nouvelle salle
                    </a>
                    @endif
                </div>
            </div>

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

            <!-- Rooms Table -->
            <div class="bg-white backdrop-blur-sm rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl border border-gray-200">
                @if($rooms->count())
                <div class="overflow-x-auto w-full">
                    <table class="w-full border-collapse bg-white">
                        <thead class="bg-gradient-to-r from-yellow-50 to-yellow-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b border-yellow-200">Nom</th>
                                <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b border-yellow-200">Code</th>
                                <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b border-yellow-200">Type</th>
                                <th class="px-4 py-3 text-right text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b border-yellow-200">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($rooms as $room)
                            <tr class="hover:bg-yellow-50/30 transition-colors duration-200">
                                <td class="px-4 py-3 text-gray-800 border-b border-gray-200">{{ $room->name }}</td>
                                <td class="px-4 py-3 text-gray-800 border-b border-gray-200">{{ $room->code }}</td>
                                <td class="px-4 py-3 text-gray-800 border-b border-gray-200">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-3 py-1.5 text-xs md:text-sm font-bold rounded-full shadow-sm bg-yellow-100 text-yellow-800" id="current-type-{{ $room->id }}">
                                            {{ $room->type }}
                                        </span>
                                        @if(auth()->user()->role === 'superadmin')
                                        <button onclick="openTypeModal('{{ $room->id }}', '{{ $room->type }}')" 
                                                class="text-yellow-600 hover:text-yellow-800 transform hover:scale-110 transition duration-200">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right space-x-2 md:space-x-3 border-b border-gray-200">
                                    <a href="{{ route('superadmin.locations.rooms.materials', ['location' => $locationId, 'room' => $room->id]) }}"
                                       class="text-blue-600 hover:text-blue-800 transform hover:scale-110 transition duration-200 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg font-bold border border-blue-200">
                                        <i class="fas fa-eye mr-1"></i>
                                        <span class="hidden md:inline">Matériels</span>
                                    </a>
                                    
                                    @if(auth()->user()->role === 'superadmin')
                                    <button onclick="openDeleteModal('{{ $room->id }}', '{{ $room->name }}')"
                                            class="text-red-600 hover:text-red-800 transform hover:scale-110 transition duration-200 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg font-bold border border-red-200">
                                        <i class="fas fa-trash-alt mr-1"></i>
                                        <span class="hidden md:inline">Supprimer</span>
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
                    <i class="fas fa-door-open text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Pas de salles dans cette localité.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Type Change Modal - Only needed for superadmin -->
@if(auth()->user()->role === 'superadmin')
<div id="typeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md border-2 border-yellow-200">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Modifier le type de salle</h3>
        
        <form id="typeChangeForm">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="room_id" id="modal-room-id">
            
            <div class="space-y-3 mb-6">
                @foreach(['Bureau', 'Salle reunion', 'Salle reseau'] as $type)
                <div class="flex items-center">
                    <input type="radio" id="type-{{ $type }}" name="type" value="{{ $type }}" 
                           class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300">
                    <label for="type-{{ $type }}" class="ml-3 block text-gray-700">
                        {{ $type }}
                    </label>
                </div>
                @endforeach
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeTypeModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    Annuler
                </button>
                <button type="button" id="confirmChangeBtn"
                        class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    Confirmer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md border-2 border-yellow-200">
        <h3 class="text-xl font-bold text-red-600 mb-4">Confirmer la suppression</h3>
        <p class="text-gray-700 mb-2">Êtes-vous sûr de vouloir supprimer la salle :</p>
        <p class="text-gray-900 font-semibold mb-1" id="room-to-delete-name"></p>
        <p class="text-red-500 text-sm mb-6">Attention: Cette action supprimera également tous les matériels associés.</p>
        
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="room_id" id="delete-room-id">
            
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
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Type Modal Functions
    window.openTypeModal = function(roomId, currentType) {
        const modal = document.getElementById('typeModal');
        if (!modal) return;
        
        const roomIdInput = document.getElementById('modal-room-id');
        roomIdInput.value = roomId;
        
        document.querySelectorAll('input[name="type"]').forEach(radio => {
            radio.checked = false;
        });
        
        const currentRadio = document.querySelector(`input[name="type"][value="${currentType}"]`);
        if (currentRadio) {
            currentRadio.checked = true;
        }
        
        modal.classList.remove('hidden');
    };
    
    window.closeTypeModal = function() {
        const modal = document.getElementById('typeModal');
        if (modal) modal.classList.add('hidden');
    };

    // Delete Modal Functions
    window.openDeleteModal = function(roomId, roomName) {
        const modal = document.getElementById('deleteModal');
        if (!modal) return;
        
        document.getElementById('delete-room-id').value = roomId;
        document.getElementById('room-to-delete-name').textContent = roomName;
        document.getElementById('deleteForm').action = `/superadmin/locations/{{ $locationId }}/rooms/${roomId}`;
        modal.classList.remove('hidden');
    };
    
    window.closeDeleteModal = function() {
        const modal = document.getElementById('deleteModal');
        if (modal) modal.classList.add('hidden');
    };

    // Handle type change confirmation
    const confirmBtn = document.getElementById('confirmChangeBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            const form = document.getElementById('typeChangeForm');
            const formData = new FormData(form);
            const roomId = document.getElementById('modal-room-id').value;
            const submitButton = this;
            
            if (!form.querySelector('input[name="type"]:checked')) {
                alert('Veuillez sélectionner un type de salle');
                return;
            }
            
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> En cours...';
            
            fetch(`/superadmin/locations/{{ $locationId }}/rooms/${roomId}/update-type`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const newType = form.querySelector('input[name="type"]:checked').value;
                document.getElementById(`current-type-${roomId}`).textContent = newType;
                closeTypeModal();
                alert('Type de salle mis à jour avec succès!');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la mise à jour: ' + error.message);
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Confirmer';
            });
        });
    }
});
</script>

<style>
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }

.animate-fadeIn { animation: fadeIn 0.6s ease-out forwards; }
.animate-fadeInUp { animation: fadeInUp 0.6s ease-out forwards; }
.animate-slideInLeft { animation: slideInLeft 0.6s ease-out forwards; }

/* Modal styles */
.hidden { display: none !important; }
#typeModal, #deleteModal { 
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