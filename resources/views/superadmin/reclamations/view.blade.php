@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Background Blur -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

    <!-- Main Container -->
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-2xl p-4 md:p-8 w-full max-w-4xl mx-auto mt-4 md:mt-8 transition-all duration-500">

            <!-- Reclamation Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 border-b border-blue-200 pb-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Réclamation #{{ $reclamation->num_R }}</h1>
                    <div class="flex items-center mt-2">
                        @php
                            $statusClass = match($reclamation->state) {
                                'nouvelle' => 'bg-red-500',
                                'en_cours' => 'bg-yellow-500',
                                'traitée' => 'bg-green-500',
                                default => 'bg-gray-500'
                            };
                        @endphp
                        <span class="px-3 py-1 text-xs font-bold rounded-full text-white {{ $statusClass }}">
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
                <div class="bg-blue-50/50 border border-blue-100 rounded-lg p-4">
                    <p class="text-gray-800 whitespace-pre-line">{{ $reclamation->message }}</p>
                </div>
            </div>

            <!-- Status Actions -->
            @if($reclamation->state === 'nouvelle')
                <form action="{{ route('superadmin.reclamations.update-status', ['id' => $reclamation->id, 'status' => 'en_cours']) }}" method="POST" class="mb-6">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-300 flex items-center">
                        <i class="fas fa-play-circle mr-2"></i> Prendre en charge
                    </button>
                </form>
            @elseif($reclamation->state === 'en_cours' && $reclamation->handler_id === Auth::id())
                <form action="{{ route('superadmin.reclamations.update-status', ['id' => $reclamation->id, 'status' => 'traitée']) }}" method="POST" class="mb-6">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-300 flex items-center">
                        <i class="fas fa-check-circle mr-2"></i> Marquer comme traitée
                    </button>
                </form>
            @endif

            <!-- Messages Section -->
            <div class="mt-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2">Conversation</h3>

                <div class="space-y-4 mb-6 max-h-96 overflow-y-auto pr-2">
                    @forelse($reclamation->messages as $message)
                        <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs md:max-w-md rounded-lg p-3 
                                {{ $message->sender_id === Auth::id() 
                                    ? 'bg-blue-500 text-white rounded-br-none' 
                                    : 'bg-gray-200 text-gray-800 rounded-bl-none' }}">
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200"
                                placeholder="Écrivez votre réponse..." required></textarea>
                        </div>
                        <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md transition duration-300">
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
                   class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>
@endsection