@extends('layouts.app')

@section('content')
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
                                    $status = $reclamation->messages->count() > 0 ? 'Traité' : 'En attente';
                                    $statusClass = $status === 'Traité' ? 'bg-green-600' : 'bg-yellow-500';
                                @endphp
                                <span class="px-3 py-1.5 text-xs font-bold rounded-full text-white {{ $statusClass }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center space-x-2">
                                    <a href="#" class="text-blue-600 hover:text-blue-800 transition" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="text-yellow-500 hover:text-yellow-700 transition" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('superadmin.reclamations.destroy', $reclamation->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
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
@endsection
