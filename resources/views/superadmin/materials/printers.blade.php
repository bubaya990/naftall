@extends('layouts.app')

@section('title', 'Liste des Imprimantes')

@section('content')
<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        <!-- Printers List Section -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-2xl p-4 md:p-8 w-full mx-auto mt-4 md:mt-8 transition-all duration-500 transform hover:scale-[1.005]">
            <!-- Header with title and actions -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-900">Liste des Imprimantes</h1>
                    <p class="text-yellow-600 mt-2">{{ count($printers) }} imprimante(s) trouvée(s)</p>
                </div>
                <div class="flex gap-3">
                <a href="{{ route('superadmin.materials.printercreate') }}" 
   class="btn btn-primary transform hover:scale-105 transition-transform duration-300">
   <i class="fas fa-plus mr-2"></i>Ajouter une imprimante
</a>

                    <a href="{{ route('superadmin.materials.index') }}" 
                       class="btn btn-secondary transform hover:scale-105 transition-transform duration-300">
                       <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                    <div class="relative">
                        <input type="text" placeholder="Rechercher..." 
                               class="pl-10 pr-4 py-2 border border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               id="printerSearch">
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
                                <th class="px-6 py-3 text-left text-xs md:text-sm font-bold text-blue-900 uppercase tracking-wider">N° Inventaire</th>
                                <th class="px-6 py-3 text-left text-xs md:text-sm font-bold text-blue-900 uppercase tracking-wider">Marque</th>
                                <th class="px-6 py-3 text-left text-xs md:text-sm font-bold text-blue-900 uppercase tracking-wider">Modèle</th>
                                <th class="px-6 py-3 text-left text-xs md:text-sm font-bold text-blue-900 uppercase tracking-wider">Site</th>
                                <th class="px-6 py-3 text-left text-xs md:text-sm font-bold text-blue-900 uppercase tracking-wider">Emplacement</th>
                                <th class="px-6 py-3 text-left text-xs md:text-sm font-bold text-blue-900 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs md:text-sm font-bold text-blue-900 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/70 divide-y divide-blue-200/30" id="printerTableBody">
                            @forelse($printers as $material)
                                @php
                                    $printer = $material->materialable;
                                    $location = $material->room ?: $material->corridor;
                                    $site = $location ? $location->location->site : null;
                                @endphp
                                <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm md:text-base font-medium text-gray-900">
                                        {{ $material->inventory_number ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm md:text-base text-gray-800">
                                        {{ $printer->printer_brand ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm md:text-base text-gray-800">
                                        {{ $printer->printer_model ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm md:text-base text-gray-800">
                                        {{ $site->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm md:text-base text-gray-800">
                                        @if($material->room)
                                            Salle {{ $material->room->name }}
                                        @elseif($material->corridor)
                                            Couloir {{ $material->corridor->name }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs md:text-sm font-semibold rounded-full 
                                            {{ $material->state === 'active' ? 'bg-green-100 text-green-800' : 
                                               ($material->state === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($material->state) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('superadmin.materials.show', ['type' => 'printer', 'material' => $material->id]) }}" 
                                               class="text-blue-600 hover:text-blue-900 transform hover:scale-110 transition-transform"
                                               title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('superadmin.materials.edit', ['type' => 'printer', 'material' => $material->id]) }}" 
                                               class="text-yellow-600 hover:text-yellow-900 transform hover:scale-110 transition-transform"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('superadmin.materials.destroy', ['type' => 'printer', 'material' => $material->id]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 transform hover:scale-110 transition-transform"
                                                        title="Supprimer"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette imprimante ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        Aucune imprimante trouvée.
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
                    <i class="fas fa-print text-blue-500 mr-2"></i>
                    Total: {{ count($printers) }} imprimante(s)
                </div>
                <div class="flex gap-2">
                    <span class="bg-green-100/50 px-2 py-1 rounded-full text-xs flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span> Active
                    </span>
                    <span class="bg-yellow-100/50 px-2 py-1 rounded-full text-xs flex items-center">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-1"></span> Maintenance
                    </span>
                    <span class="bg-red-100/50 px-2 py-1 rounded-full text-xs flex items-center">
                        <span class="w-2 h-2 bg-red-500 rounded-full mr-1"></span> Inactive
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
    
    .btn {
        @apply flex items-center px-4 py-2 rounded-lg transition-all duration-300;
    }
    .btn-primary {
        @apply bg-blue-600 hover:bg-blue-700 text-white;
    }
    .btn-secondary {
        @apply bg-white border border-blue-600 text-blue-600 hover:bg-blue-50;
    }
</style>

<script>
    // Simple search functionality for printers
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('printerSearch');
        const tableBody = document.getElementById('printerTableBody');
        const rows = tableBody.querySelectorAll('tr');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            rows.forEach(row => {
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