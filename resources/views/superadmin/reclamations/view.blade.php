@extends('layouts.app')

@section('content') 
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Enhanced Background with Layered Blur -->
<div class="relative min-h-screen">
    <!-- Background with stronger blur at edges -->
    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('/image/background.webp'); filter: blur(12px); transform: scale(1.05);"></div>
        <div class="absolute inset-0 bg-black bg-opacity-10 backdrop-blur-sm"></div>
    </div>

    <!-- Main Container with Glass Morphism Effect -->
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        @if(isset($reclamation))
            <!-- Single Reclamation View with Glass Card -->
            <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl p-6 border-l-4 border-purple-600 transition-all duration-300 hover:shadow-2xl">
                <!-- Reclamation Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 border-b border-purple-100 pb-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Réclamation #{{ $reclamation->num_R }}</h1>
                        <div class="flex items-center mt-2">
                            @php
                                $statusClass = match($reclamation->state) {
                                    'nouvelle' => 'bg-red-500 animate-pulse',
                                    'en_cours' => 'bg-purple-600',
                                    'traitée' => 'bg-green-600',
                                    default => 'bg-gray-500'
                                };
                            @endphp
                            <span class="px-3 py-1 text-xs font-bold rounded-full text-white {{ $statusClass }} transition-colors duration-300">
                                {{ ucfirst($reclamation->state) }}
                            </span>
                            @if($reclamation->state === 'en_cours' || $reclamation->state === 'traitée')
                                <span class="ml-2 text-sm text-gray-600">
                                    Traité par: {{ $reclamation->handler->name ?? 'N/A' }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">
                        <div>Créée le: {{ $reclamation->date_R }}</div>
                        <div>Par: {{ $reclamation->user->name }}</div>
                    </div>
                </div>

              <!-- Reclamation Content -->
<div class="mb-8">
    <h2 class="text-lg font-bold text-gray-800 mb-2">{{ $reclamation->definition }}</h2>
    <div class="bg-gray-50/80 rounded-lg p-4 border-2 border-purple-400 backdrop-blur-sm transition-all duration-300 hover:border-purple-500 hover:shadow-md">
        <p class="text-gray-800 whitespace-pre-line">{{ $reclamation->message }}</p>
    </div>
</div>
                <!-- Status Actions -->
                @if($reclamation->state === 'nouvelle')
                    <form action="{{ route('superadmin.reclamations.update-status', ['id' => $reclamation->id, 'status' => 'en_cours']) }}" method="POST" class="mb-6">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 flex items-center transform hover:-translate-y-0.5">
                            <i class="fas fa-play-circle mr-2"></i> Prendre en charge
                        </button>
                    </form>
                @elseif($reclamation->state === 'en_cours' && $reclamation->handler_id === Auth::id())
                    <form action="{{ route('superadmin.reclamations.update-status', ['id' => $reclamation->id, 'status' => 'traitée']) }}" method="POST" class="mb-6">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 flex items-center transform hover:-translate-y-0.5">
                            <i class="fas fa-check-circle mr-2"></i> Marquer comme traitée
                        </button>
                    </form>
                @endif

                <!-- Messages Section -->
<div class="mt-8">
    <h3 class="text-xl font-bold text-gray-800 mb-4 border-b border-purple-200 pb-2">Conversation</h3>

    <div class="space-y-4 mb-6 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
        @forelse($reclamation->messages as $message)
            <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs md:max-w-md rounded-lg p-3 transition-all duration-300
                    {{ $message->sender_id === Auth::id() 
                        ? 'bg-purple-600 text-white rounded-br-none hover:shadow-md ' 
                        : 'bg-gray-50/80 border-2 border-purple-400 text-gray-800 rounded-bl-none hover:shadow-md hover:border-purple-500' }}">
                    <div class="text-sm font-semibold">
                        {{ $message->sender->name }}
                        <span class="text-xs opacity-70 ml-2">
                            {{ $message->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <p class="mt-1 whitespace-pre-line">{{ $message->message }}</p>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-4">Aucun message pour cette réclamation</div>
        @endforelse
    </div>

                    @if($reclamation->state !== 'traitée')
                        <form action="{{ route('superadmin.reclamations.store-message', $reclamation->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Votre réponse</label>
                                <textarea name="message" id="message" rows="3" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-purple-200 bg-white/80 backdrop-blur-sm transition-all duration-300 focus:border-purple-500"
                                    placeholder="Écrivez votre réponse..." required></textarea>
                            </div>
                            <button type="submit" 
                                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                                <i class="fas fa-paper-plane mr-2"></i> Envoyer
                            </button>
                        </form>
                    @else
                        <div class="text-center text-gray-500 py-4">
                            Cette réclamation est marquée comme traitée. Aucun nouveau message ne peut être ajouté.
                        </div>
                    @endif
                </div>

                <!-- Back Button -->
                <div class="mt-8">
                    <a href="{{ route('superadmin.reclamations') }}" 
                       class="text-purple-600 hover:text-purple-800 font-medium flex items-center transition-colors duration-300">
                        <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                    </a>
                </div>
            </div>
        @else
            <!-- Reclamations List View with Glass Card -->
            <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl p-6 border-l-4 border-purple-600 transition-all duration-300 hover:shadow-2xl">
                
                <!-- Header with actions -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800">Liste des Réclamations</h1>
                        <p class="text-gray-600 mt-1 text-sm md:text-base">Liste complète des réclamations</p>
                    </div>
                
                    <a href="{{ route('superadmin.reclamations.addreclamation') }}" 
                       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 flex items-center transform hover:-translate-y-0.5">
                        <i class="fas fa-plus-circle mr-2"></i> Nouvelle Réclamation
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100/90 backdrop-blur-sm text-green-800 rounded-lg border-l-4 border-green-600 animate-fade-in">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100/90 backdrop-blur-sm text-red-800 rounded-lg border-l-4 border-red-600 animate-fade-in">
                        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                    </div>
                @endif

                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm overflow-hidden border border-gray-200 transition-all duration-300 hover:shadow-md">
                    <div class="p-6">
                        @if($reclamations->count())
                            <div class="overflow-x-auto w-full">
                                <table class="w-full border-collapse">
                                    <!-- Table Header -->
                                    <thead class="bg-gray-50/80 backdrop-blur-sm">
                                        <tr class="text-gray-700">
                                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold uppercase border-b border-gray-200">Numéro</th>
                                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold uppercase border-b border-gray-200">Date</th>
                                            <th class="px-4 py-3 text-left text-sm md:text-text-base font-bold uppercase border-b border-gray-200">Utilisateur</th>
                                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold uppercase border-b border-gray-200">Message</th>
                                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold uppercase border-b border-gray-200">Statut</th>
                                            <th class="px-4 py-3 text-right text-sm md:text-base font-bold uppercase border-b border-gray-200">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($reclamations as $reclamation)
                                            <tr class="hover:bg-gray-50/60 transition-colors duration-200">
                                                <td class="px-4 py-3 text-gray-800">{{ $reclamation->num_R }}</td>
                                                <td class="px-4 py-3 text-gray-800">{{ $reclamation->date_R }}</td>
                                                <td class="px-4 py-3 text-gray-800">{{ $reclamation->user->name }}</td>
                                                <td class="px-4 py-3 text-gray-800 truncate max-w-xs">{{ Str::limit($reclamation->message, 50) }}</td>
                                                <td class="px-4 py-3 text-gray-800">
                                                    @php
                                                        $status = $reclamation->state;
                                                        $statusClass = match($status) {
                                                            'nouvelle' => 'bg-red-500',
                                                            'en_cours' => 'bg-purple-600',
                                                            'traitée' => 'bg-green-600',
                                                            default => 'bg-gray-500'
                                                        };
                                                    @endphp
                                                    <span class="px-3 py-1 text-xs font-bold rounded-full text-white {{ $statusClass }} transition-colors duration-300">
                                                        {{ ucfirst($status) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-right space-x-2">
                                                    <a href="{{ route('superadmin.reclamations.show', $reclamation->id) }}"
                                                       class="inline-flex items-center text-purple-600 hover:text-purple-800 px-3 py-1.5 rounded-lg transition-all duration-200 hover:bg-purple-50">
                                                        <i class="fas fa-eye mr-1"></i>
                                                        <span class="whitespace-nowrap">Voir</span>
                                                    </a>
                                                    
                                                    <button onclick="openDeleteModal('{{ $reclamation->id }}', '{{ $reclamation->num_R }}', '{{ $reclamation->user->name }}')"
                                                            class="inline-flex items-center text-red-600 hover:text-red-800 px-3 py-1.5 rounded-lg transition-all duration-200 hover:bg-red-50">
                                                        <i class="fas fa-trash-alt mr-1"></i>
                                                        <span class="whitespace-nowrap">Supprimer</span>
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
                                <i class="fas fa-exclamation-circle text-4xl text-purple-400 mb-3 animate-bounce"></i>
                                <p class="text-gray-500">Aucune réclamation trouvée.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Enhanced Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity duration-300">
    <div class="bg-white/95 backdrop-blur-lg rounded-xl shadow-2xl p-6 w-full max-w-md border-2 border-purple-300 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <h3 class="text-xl font-bold text-red-600 mb-4">Confirmer la suppression</h3>
        <p class="text-gray-700 mb-2">Êtes-vous sûr de vouloir supprimer la réclamation :</p>
        <div class="bg-gray-50/80 p-3 rounded-lg mb-4 backdrop-blur-sm">
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
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200">
                    Annuler
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200 transform hover:scale-105">
                    Confirmer la suppression
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced Delete Modal Functions
    window.openDeleteModal = function(reclamationId, reclamationNumber, userName) {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('modalContent');
        
        document.getElementById('delete-reclamation-id').value = reclamationId;
        document.getElementById('reclamation-number').textContent = 'Réclamation #' + reclamationNumber;
        document.getElementById('reclamation-user').textContent = userName;
        document.getElementById('deleteForm').action = `/superadmin/reclamations/${reclamationId}`;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    };

    window.closeDeleteModal = function() {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('modalContent');
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    };
});
</script>

<style>
/* Enhanced Color scheme */
.bg-purple-600 { background-color: #7c3aed; }
.bg-purple-700 { background-color: #6d28d9; }
.text-purple-600 { color: #7c3aed; }
.text-purple-700 { color: #6d28d9; }
.text-purple-800 { color: #5b21b6; }
.border-purple-600 { border-color: #7c3aed; }

/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(0,0,0,0.05);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(124,58,237,0.5);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(124,58,237,0.7);
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fadeIn 0.3s ease-out forwards;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}
.animate-bounce {
    animation: bounce 1s infinite;
}

/* Hover effects */
.hover\:bg-purple-700:hover { background-color: #6d28d9; }
.hover\:text-purple-800:hover { color: #5b21b6; }

/* Table styles */
table {
    border-collapse: separate;
    border-spacing: 0;
}

th, td {
    border-bottom: 1px solid rgba(229, 231, 235, 0.7);
}

/* Button transitions */
button, a {
    transition: all 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .flex-col.md\:flex-row {
        flex-direction: column;
    }
    
    .space-x-2 > * {
        margin-bottom: 0.5rem;
    }
    
    .inline-flex {
        display: inline-flex;
        width: 100%;
        justify-content: center;
    }
}

/* Glass morphism effect */
.backdrop-blur-md {
    backdrop-filter: blur(12px);
}
.bg-white\/90 {
    background-color: rgba(255, 255, 255, 0.9);
}
</style>

@endsection