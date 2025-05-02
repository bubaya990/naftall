@extends('layouts.app')

@section('content')
<!-- CSRF Token Meta Tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="relative">
    <!-- Background Blur -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

    <!-- Main Container -->
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-2xl p-4 md:p-8 w-full mx-auto mt-4 md:mt-8 transition-all duration-500 transform hover:scale-[1.005]">

            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl md:text-4xl font-extrabold text-gray-900 animate-fadeIn">Liste des Réclamations</h1>
                    <p class="text-gray-600 font-medium animate-fadeIn">Suivi des plaintes utilisateurs</p>
                </div>
                <a href="{{ route('superadmin.reclamations.addreclamation') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2.5 rounded-full shadow-md transform hover:scale-105 transition duration-300 animate-bounceIn">
                    <i class="fas fa-plus-circle mr-2"></i>Nouvelle Réclamation
                </a>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto w-full backdrop-filter backdrop-blur-lg bg-blue-900/30 rounded-xl shadow-lg border border-blue-800/20 animate-fadeInUp">
                <table class="w-full border-collapse">
                    <thead class="bg-blue-900/60 text-white text-sm uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Numéro</th>
                            <th class="px-4 py-3 text-left">Date</th>
                            <th class="px-4 py-3 text-left">Utilisateur</th>
                            <th class="px-4 py-3 text-left">Message</th>
                            <th class="px-4 py-3 text-left">Statut</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-800/30 text-gray-900">
                        @forelse($reclamations as $index => $reclamation)
                        <tr class="hover:bg-blue-900/10 transition-all duration-300 ease-in-out transform hover:translate-x-1 animate-fadeIn" style="animation-delay: {{ $index * 50 }}ms">
                            <td class="px-4 py-3">{{ $reclamation->num_R }}</td>
                            <td class="px-4 py-3">{{ $reclamation->date_R }}</td>
                            <td class="px-4 py-3">{{ $reclamation->user->name }}</td>
                            <td class="px-4 py-3 truncate max-w-xs">{{ Str::limit($reclamation->message, 50) }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $status = $reclamation->state;
                                    $statusClass = match($status) {
                                        'nouvelle' => 'bg-red-500',
                                        'en_cours' => 'bg-yellow-500',
                                        'traitée' => 'bg-green-500',
                                        default => 'bg-gray-500'
                                    };
                                @endphp
                                <span class="px-3 py-1.5 text-xs font-bold rounded-full text-white {{ $statusClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center space-x-4">
                                    <a href="{{ route('superadmin.reclamations.show', $reclamation->id) }}" class="text-blue-600 hover:text-blue-800 transition" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <button onclick="openDeleteModal('{{ $reclamation->id }}', '{{ $reclamation->num_R }}', '{{ $reclamation->user->name }}')" 
                                            class="text-red-600 hover:text-red-800 transition" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-6">Aucune réclamation trouvée</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 text-center">
                {{ $reclamations->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md">
        <h3 class="text-xl font-bold text-red-600 mb-4">Confirmer la suppression</h3>
        <p class="text-gray-700 mb-2">Êtes-vous sûr de vouloir supprimer la réclamation :</p>
        <div class="bg-gray-50 p-3 rounded-lg mb-4">
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

<style>
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes bounceIn { 0% { opacity: 0; transform: scale(0.8); } 50% { opacity: 1; transform: scale(1.05); } 100% { transform: scale(1); } }

.animate-fadeIn { animation: fadeIn 0.6s ease-out forwards; }
.animate-fadeInUp { animation: fadeInUp 0.6s ease-out forwards; }
.animate-bounceIn { animation: bounceIn 0.6s ease-out forwards; }
</style>

<script>
// Delete Modal Functions
function openDeleteModal(reclamationId, reclamationNumber, userName) {
    const modal = document.getElementById('deleteModal');
    const reclamationIdInput = document.getElementById('delete-reclamation-id');
    const reclamationNumberSpan = document.getElementById('reclamation-number');
    const userSpan = document.getElementById('reclamation-user');
    
    reclamationIdInput.value = reclamationId;
    reclamationNumberSpan.textContent = 'Réclamation #' + reclamationNumber;
    userSpan.textContent = userName;
    
    // Set the form action
    document.getElementById('deleteForm').action = `/superadmin/reclamations/${reclamationId}`;
    
    modal.classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection