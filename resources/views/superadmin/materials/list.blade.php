@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        <!-- Materials List Section -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-2xl p-4 md:p-8 w-full mx-auto mt-4 md:mt-8 transition-all duration-500 transform hover:scale-[1.005]">
            <!-- Header with actions -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="animate-fadeIn">
                    <h1 class="text-2xl md:text-4xl font-extrabold text-gray-900">
                        @if($type == 'computers') Ordinateurs
                        @elseif($type == 'printers') Imprimantes
                        @elseif($type == 'ip-phones') Téléphones IP
                        @elseif($type == 'hotspots') Hotspots
                        @endif
                    </h1>
                    <p class="text-blue-600 mt-1 text-sm md:text-base">Liste complète des équipements</p>
                </div>
                <a href="{{ route('superadmin.materials.create', ['type' => $type]) }}" 
                   class="btn btn-primary transform hover:scale-105 transition-transform duration-300 animate-bounceIn">
                   <i class="fas fa-plus-circle mr-2"></i>Ajouter
                </a>
            </div>

            <!-- Materials Table -->
            <div class="overflow-x-auto w-full backdrop-filter backdrop-blur-lg bg-blue-900/30 rounded-xl shadow-lg border border-blue-800/20 animate-fadeInUp">
                <table class="w-full border-collapse">
                    <!-- Search headers row -->
                    <thead class="bg-blue-900/20 backdrop-blur-sm">
                        <tr class="animate-fadeIn">
                            <!-- Inventory Number Search -->
                            <th class="px-2 py-2">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-barcode text-blue-600 text-xs"></i>
                                    </div>
                                    <input type="text" 
                                           class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-blue-400 rounded-full bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800" 
                                           placeholder="N° Inventaire" 
                                           id="search-inventory"
                                           data-column="0" />
                                </div>
                            </th>
                            
                            <!-- Serial Number Search -->
                            <th class="px-2 py-2">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-hashtag text-blue-600 text-xs"></i>
                                    </div>
                                    <input type="text" 
                                           class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-blue-400 rounded-full bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800" 
                                           placeholder="N° Série" 
                                           id="search-serial"
                                           data-column="1" />
                                </div>
                            </th>
                            
                            <!-- State Search -->
                            <th class="px-2 py-2">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-check-circle text-blue-600 text-xs"></i>
                                    </div>
                                    <select class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-blue-400 rounded-full bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800 appearance-none"
                                            id="search-state"
                                            data-column="2">
                                        <option value="">État</option>
                                        <option value="bon">Bon</option>
                                        <option value="défectueux">Défectueux</option>
                                        <option value="hors service">Hors service</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <i class="fas fa-chevron-down text-blue-600 text-xs"></i>
                                    </div>
                                </div>
                            </th>
                            
                            <!-- Site Search -->
                            <th class="px-2 py-2">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-building text-blue-600 text-xs"></i>
                                    </div>
                                    <input type="text" 
                                           class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-blue-400 rounded-full bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800" 
                                           placeholder="Site" 
                                           id="search-site"
                                           data-column="3" />
                                </div>
                            </th>
                            
                            <!-- Location Search -->
                            <th class="px-2 py-2">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-blue-600 text-xs"></i>
                                    </div>
                                    <input type="text" 
                                           class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-blue-400 rounded-full bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800" 
                                           placeholder="Emplacement" 
                                           id="search-location"
                                           data-column="4" />
                                </div>
                            </th>
                            
                            <!-- Type-specific search -->
                            @if($type == 'computers')
                            <th class="px-2 py-2">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-desktop text-blue-600 text-xs"></i>
                                    </div>
                                    <input type="text" 
                                           class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-blue-400 rounded-full bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800" 
                                           placeholder="Marque" 
                                           id="search-brand"
                                           data-column="5" />
                                </div>
                            </th>
                            @elseif($type == 'printers')
                            <th class="px-2 py-2">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-print text-blue-600 text-xs"></i>
                                    </div>
                                    <input type="text" 
                                           class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-blue-400 rounded-full bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800" 
                                           placeholder="Marque" 
                                           id="search-brand"
                                           data-column="5" />
                                </div>
                            </th>
                            @elseif($type == 'ip-phones')
                            <th class="px-2 py-2">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-blue-600 text-xs"></i>
                                    </div>
                                    <input type="text" 
                                           class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-blue-400 rounded-full bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800" 
                                           placeholder="MAC" 
                                           id="search-mac"
                                           data-column="5" />
                                </div>
                            </th>
                            @elseif($type == 'hotspots')
                            <th class="px-2 py-2">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-key text-blue-600 text-xs"></i>
                                    </div>
                                    <input type="text" 
                                           class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-blue-400 rounded-full bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800" 
                                           placeholder="Mot de passe" 
                                           id="search-password"
                                           data-column="5" />
                                </div>
                            </th>
                            @endif
                            
                            <!-- Actions header (empty) -->
                            <th class="px-4 py-3 text-right text-sm md:text-base font-bold text-white uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    
                    <!-- Column headers -->
                    <thead class="bg-blue-900/70 backdrop-blur-sm">
                        <tr class="animate-fadeIn">
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">N° Inventaire</th>
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">N° Série</th>
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">État</th>
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">Site</th>
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">Emplacement</th>
                            @if($type == 'computers')
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">Marque</th>
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">Modèle</th>
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">OS</th>
                            @elseif($type == 'printers')
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">Marque</th>
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">Modèle</th>
                            @elseif($type == 'ip-phones')
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">MAC</th>
                            @elseif($type == 'hotspots')
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">Mot de passe</th>
                            @endif
                            <th class="px-4 py-3 text-right text-sm md:text-base font-bold text-white uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-blue-800/30" id="materialsTable">
                        @if ($materials->isEmpty())
                            <tr>
                                <td colspan="10" class="px-4 py-6 text-center text-lg text-gray-600 animate-fadeIn">
                                    Aucun matériel trouvé pour ce type.
                                </td>
                            </tr>
                        @else
                            @foreach($materials as $index => $material)
                            <tr class="hover:bg-blue-900/20 transition-all duration-300 ease-in-out transform hover:translate-x-1 animate-fadeIn" style="animation-delay: {{ $index * 50 }}ms">
                                <td class="px-4 py-3 text-gray-900">{{ $material->inventory_number }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $material->serial_number }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $stateColors = [
                                            'bon' => 'bg-green-600 text-white',
                                            'défectueux' => 'bg-yellow-600 text-white',
                                            'hors service' => 'bg-red-600 text-white',
                                        ];
                                        $stateClass = $stateColors[$material->state] ?? 'bg-gray-600 text-white';
                                    @endphp
                                    <span class="px-3 py-1.5 text-xs md:text-sm font-bold rounded-full shadow-md {{ $stateClass }}">
                                        {{ ucfirst($material->state) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-900">
                                    {{ $material->room->location->site->name ?? $material->corridor->location->site->name ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-gray-900">
                                    @if($material->room)
                                    Salle: {{ $material->room->name }}
                                    @elseif($material->corridor)
                                    Couloir
                                    @endif
                                </td>
                                
                                @if($type == 'computers')
                                <td class="px-4 py-3 text-gray-900">{{ $material->materialable->computer_brand }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $material->materialable->computer_model }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $material->materialable->OS }}</td>
                                @elseif($type == 'printers')
                                <td class="px-4 py-3 text-gray-900">{{ $material->materialable->printer_brand }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $material->materialable->printer_model }}</td>
                                @elseif($type == 'ip-phones')
                                <td class="px-4 py-3 text-gray-900">{{ $material->materialable->mac_number }}</td>
                                @elseif($type == 'hotspots')
                                <td class="px-4 py-3 text-gray-900">{{ $material->materialable->password }}</td>
                                @endif
                                
                                <td class="px-4 py-3 text-right space-x-2 md:space-x-3">
                                    <form action="{{ route('superadmin.materials.destroy', ['type' => $type, 'id' => $material->id]) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 transform hover:scale-110 transition duration-200 bg-white/60 hover:bg-red-100 px-3 py-1.5 rounded-lg font-bold"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce matériel?')">
                                            <i class="fas fa-trash-alt mr-1"></i>
                                            <span class="hidden md:inline">Supprimer</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes bounceIn { 0% { opacity: 0; transform: scale(0.8); } 50% { opacity: 1; transform: scale(1.05); } 100% { transform: scale(1); } }
@keyframes slideInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }

.animate-fadeIn { animation: fadeIn 0.6s ease-out forwards; }
.animate-fadeInUp { animation: fadeInUp 0.6s ease-out forwards; }
.animate-bounceIn { animation: bounceIn 0.6s ease-out forwards; }
.animate-slideInDown { animation: slideInDown 0.4s ease-out forwards; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all search inputs
    const searchInputs = document.querySelectorAll('input[id^="search-"], select[id^="search-"]');
    const rows = document.querySelectorAll('#materialsTable tr');

    // Function to filter rows based on all search criteria
    function filterRows() {
        const filters = {};
        
        // Collect all filter values
        searchInputs.forEach(input => {
            const column = input.getAttribute('data-column');
            filters[column] = input.value.toLowerCase();
        });

        rows.forEach(row => {
            if (row.cells.length <= 1) return; // Skip the "no results" row
            
            let visible = true;
            const cells = row.cells;

            // Check each filter
            for (const [column, value] of Object.entries(filters)) {
                if (value === '') continue;
                
                const cell = cells[column];
                let cellText = cell.textContent.toLowerCase();
                
                // Special handling for state column (span content)
                if (column === '2') {
                    const span = cell.querySelector('span');
                    if (span) {
                        cellText = span.textContent.toLowerCase();
                    }
                }
                
                if (!cellText.includes(value)) {
                    visible = false;
                    break;
                }
            }

            // Apply visibility
            if (visible) {
                row.classList.remove('hidden');
                row.style.opacity = 1;
                row.style.transform = 'translateY(0)';
            } else {
                row.style.opacity = 0;
                row.style.transform = 'translateY(-10px)';
                setTimeout(() => row.classList.add('hidden'), 300);
            }
        });
    }

    // Add event listeners to all search inputs
    searchInputs.forEach(input => {
        input.addEventListener('input', filterRows);
    });

    // Responsive data labels
    const headers = document.querySelectorAll('thead th');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        cells.forEach((cell, index) => {
            if (index < headers.length) {
                cell.setAttribute('data-label', headers[index].textContent);
            }
        });
    });
});
</script>
@endsection