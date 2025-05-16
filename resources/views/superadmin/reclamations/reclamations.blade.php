@extends('layouts.app')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="relative">
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>
   
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        <!-- Reclamations Section -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 backdrop-blur-sm rounded-xl shadow-lg p-4 border-l-4 border-purple-500 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl">
            
            <!-- Header with actions -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-2xl md:text-4xl font-extrabold text-gray-800 animate-fadeIn">Liste des Réclamations</h1>
               
                <a href="{{ route('superadmin.reclamations.addreclamation') }}" 
                   class="btn btn-primary transform hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus-circle mr-2"></i>Nouvelle Réclamation
                </a>
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

            <div class="space-y-6">
                <div class="bg-white backdrop-blur-sm rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl border border-gray-200">
                    <div class="p-6">
                        @if($reclamations->count())
                            <div class="overflow-x-auto w-full">
                                <table class="w-full border-collapse bg-white">
                                    <!-- Table Header -->
                                    <thead class="bg-gradient-to-r from-purple-50 to-purple-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b border-purple-200">Numéro</th>
                                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b border-purple-200">Date</th>
                                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b border-purple-200">Utilisateur</th>
                                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b border-purple-200">Message</th>
                                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b border-purple-200">Statut</th>
                                            <th class="px-4 py-3 text-right text-sm md:text-base font-bold text-gray-700 uppercase tracking-wider border-b border-purple-200">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($reclamations as $reclamation)
                                            <tr class="hover:bg-purple-50/30 transition-colors duration-200" data-reclamation-id="{{ $reclamation->id }}">
                                                <td class="px-4 py-3 text-gray-800 border-b border-gray-200">{{ $reclamation->num_R }}</td>
                                                <td class="px-4 py-3 text-gray-800 border-b border-gray-200">{{ $reclamation->date_R }}</td>
                                                <td class="px-4 py-3 text-gray-800 border-b border-gray-200">{{ $reclamation->user->name }}</td>
                                                <td class="px-4 py-3 text-gray-800 border-b border-gray-200 truncate max-w-xs">{{ Str::limit($reclamation->message, 50) }}</td>
                                                <td class="px-4 py-3 text-gray-800 border-b border-gray-200">
                                                    @php
                                                        $status = $reclamation->state;
                                                        $statusClass = match($status) {
                                                            'nouvelle' => 'bg-red-500',
                                                            'en_cours' => 'bg-purple-500',
                                                            'traitée' => 'bg-green-500',
                                                            default => 'bg-gray-500'
                                                        };
                                                    @endphp
                                                    <span class="px-3 py-1.5 text-xs md:text-sm font-bold rounded-full shadow-sm text-white {{ $statusClass }}">
                                                        {{ ucfirst($status) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-right space-x-2 md:space-x-3 border-b border-gray-200">
                                                    <a href="{{ route('superadmin.reclamations.show', $reclamation->id) }}"
                                                       class="text-purple-600 hover:text-purple-800 transform hover:scale-110 transition duration-200 bg-purple-50 hover:bg-purple-100 px-3 py-1.5 rounded-lg font-bold border border-purple-200">
                                                        <i class="fas fa-eye mr-1"></i>
                                                        <span class="hidden md:inline">Voir</span>
                                                    </a>
                                                    
                                                    <button onclick="openDeleteModal('{{ $reclamation->id }}', '{{ $reclamation->num_R }}', '{{ $reclamation->user->name }}')"
                                                            class="text-red-600 hover:text-red-800 transform hover:scale-110 transition duration-200 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg font-bold border border-red-200">
                                                        <i class="fas fa-trash-alt mr-1"></i>
                                                        <span class="hidden md:inline">Supprimer</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="mt-6 text-center">
                                {{ $reclamations->links() }}
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-exclamation-circle text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Aucune réclamation trouvée.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md border-2 border-purple-200">
        <h3 class="text-xl font-bold text-red-600 mb-4">Confirmer la suppression</h3>
        <p class="text-gray-700 mb-2">Êtes-vous sûr de vouloir supprimer la réclamation :</p>
        <div class="bg-purple-50 p-3 rounded-lg mb-4">
            <p class="font-semibold"><span id="reclamation-number"></span></p>
            <p class="text-sm">Utilisateur: <span id="reclamation-user" class="font-medium"></span></p>
        </div>
        <p class="text-red-500 text-sm mb-6">Attention: Cette action est irréversible.</p>
       
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="reclamation_id" id="delete-reclamation-id">
           
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500">
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
    window.openDeleteModal = function(reclamationId, reclamationNumber, userName) {
        const modal = document.getElementById('deleteModal');
        if (!modal) {
            console.error('Delete modal element not found');
            return;
        }
        
        document.getElementById('delete-reclamation-id').value = reclamationId;
        document.getElementById('reclamation-number').textContent = 'Réclamation #' + reclamationNumber;
        document.getElementById('reclamation-user').textContent = userName;
        document.getElementById('deleteForm').action = `/superadmin/reclamations/${reclamationId}`;
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

.animate-fadeIn { animation: fadeIn 0.6s ease-out forwards; }
.animate-fadeInUp { animation: fadeInUp 0.6s ease-out forwards; }

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
    background-color: rgba(237, 233, 254, 0.3) !important; /* Light purple hover */
    transition: background-color 0.2s ease;
}

/* Button transitions */
button, a {
    transition: all 0.2s ease-in-out;
}

/* Purple color accents */
.from-purple-50 { --tw-gradient-from: #faf5ff; }
.to-purple-100 { --tw-gradient-to: #f3e8ff; }
.from-purple-100 { --tw-gradient-from: #f3e8ff; }
.to-purple-200 { --tw-gradient-to: #e9d5ff; }
.bg-purple-100 { background-color: #f3e8ff; }
.bg-purple-200 { background-color: #e9d5ff; }
.border-purple-200 { border-color: #e9d5ff; }
.text-purple-700 { color: #7e22ce; }
.bg-purple-50 { background-color: #faf5ff; }

/* Action button colors */
.bg-purple-500 { background-color: #a855f7; }
.bg-purple-600 { background-color: #9333ea; }
.hover\:from-purple-600:hover { --tw-gradient-from: #9333ea; }
.hover\:to-purple-700:hover { --tw-gradient-to: #7e22ce; }
</style>

@endsection