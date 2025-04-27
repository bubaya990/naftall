@extends('layouts.app')

@section('title', 'Liste des Ordinateurs')

@section('content')
<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        <!-- Computers List Section -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-2xl p-4 md:p-8 w-full mx-auto mt-4 md:mt-8 transition-all duration-500 transform hover:scale-[1.005]">
            <!-- Header with title and actions -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Liste des Ordinateurs</h1>
                <div class="flex gap-3">
                    <a href="{{ route('superadmin.computers.create') }}" 
                       class="btn btn-primary transform hover:scale-105 transition-transform duration-300">
                       <i class="fas fa-plus mr-2"></i>Ajouter un ordinateur
                    </a>
                    <div class="relative">
                        <input type="text" placeholder="Rechercher..." 
                               class="pl-10 pr-4 py-2 border border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <i class="fas fa-search absolute left-3 top-3 text-blue-400"></i>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
                    <div class="font-bold">{{ session('success') }}</div>
                </div>
            @endif

            <!-- Table container with scrollable body -->
            <div class="overflow-hidden w-full backdrop-filter backdrop-blur-lg bg-blue-900/30 rounded-xl shadow-lg border border-blue-800/20">
                <div class="overflow-y-auto max-h-[calc(100vh-300px)]">
                    <table class="min-w-full divide-y divide-blue-200/50">
                        <thead class="bg-blue-600/10 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs md:text-sm font-bold text-blue-900 uppercase tracking-wider">Numéro de série</th>
                                <th class="px-6 py-3 text-left text-xs md:text-sm font-bold text-blue-900 uppercase tracking-wider">Modèle</th>
                                <th class="px-6 py-3 text-left text-xs md:text-sm font-bold text-blue-900 uppercase tracking-wider">Marque</th>
                                <th class="px-6 py-3 text-left text-xs md:text-sm font-bold text-blue-900 uppercase tracking-wider">Site</th>
                                <th class="px-6 py-3 text-left text-xs md:text-sm font-bold text-blue-900 uppercase tracking-wider">Localisation</th>
                                <th class="px-6 py-3 text-left text-xs md:text-sm font-bold text-blue-900 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs md:text-sm font-bold text-blue-900 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/70 divide-y divide-blue-200/30">
                            @forelse($computers as $material)
                                @php
                                    $site = $material->room?->location?->site?->name ?? $material->corridor?->location?->site?->name ?? 'Non défini';
                                    $location = $material->room?->name ?? $material->corridor?->name ?? 'Non défini';
                                @endphp
                                <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm md:text-base font-medium text-gray-900">
                                        {{ $material->serial_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm md:text-base text-gray-800">
                                        {{ $material->model }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm md:text-base text-gray-800">
                                        {{ $material->brand ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm md:text-base text-gray-800">
                                        {{ $site }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm md:text-base text-gray-800">
                                        {{ $location }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs md:text-sm font-semibold rounded-full 
                                            {{ $material->status === 'actif' ? 'bg-green-100 text-green-800' : 
                                               ($material->status === 'en panne' ? 'bg-red-100 text-red-800' : 
                                               ($material->status === 'en maintenance' ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($material->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('superadmin.materials.show', ['type' => 'computer', 'material' => $material->id]) }}" 
                                               class="text-blue-600 hover:text-blue-900 transform hover:scale-110 transition-transform"
                                               title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('superadmin.materials.edit', ['type' => 'computer', 'material' => $material->id]) }}" 
                                               class="text-yellow-600 hover:text-yellow-900 transform hover:scale-110 transition-transform"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('superadmin.materials.destroy', ['type' => 'computer', 'material' => $material->id]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 transform hover:scale-110 transition-transform"
                                                        title="Supprimer"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet ordinateur ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        Aucun ordinateur trouvé.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary footer -->
            <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
                <div class="bg-blue-100/50 px-4 py-2 rounded-lg">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    Total: {{ count($computers) }} ordinateur(s)
                </div>
                <div class="flex gap-2">
                    <span class="bg-green-100/50 px-2 py-1 rounded-full text-xs flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span> Actif
                    </span>
                    <span class="bg-yellow-100/50 px-2 py-1 rounded-full text-xs flex items-center">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-1"></span> Maintenance
                    </span>
                    <span class="bg-red-100/50 px-2 py-1 rounded-full text-xs flex items-center">
                        <span class="w-2 h-2 bg-red-500 rounded-full mr-1"></span> En panne
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom scrollbar for the table */
    .overflow-y-auto::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    .overflow-y-auto::-webkit-scrollbar-track {
        background: rgba(219, 234, 254, 0.3);
        border-radius: 10px;
    }
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: rgba(59, 130, 246, 0.5);
        border-radius: 10px;
    }
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: rgba(59, 130, 246, 0.7);
    }
</style>

<script>
    // Simple search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[type="text"]');
        const tableRows = document.querySelectorAll('tbody tr');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection