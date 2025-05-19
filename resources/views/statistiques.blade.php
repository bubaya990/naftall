@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Blurred background -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.webp'); filter: blur(6px);"></div>
    
    <div class="relative z-10 min-h-screen p-4 pb-12">
        <!-- Time Period Selector -->
        <div class="bg-white/70 backdrop-blur-lg shadow-xl rounded-xl p-4 max-w-4xl mx-auto mt-6 mb-6 border-l-4 border-blue-500">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <h2 class="text-lg font-bold text-blue-900 mb-2 md:mb-0">Période de Statistiques</h2>
                <div class="flex space-x-2">
                    <select id="timePeriod" class="bg-white border border-gray-300 text-blue-900 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <option value="month">Mois Actuel</option>
                        <option value="year">Année Actuelle</option>
                        <option value="custom">Personnalisé</option>
                    </select>
                    <div id="customDateRange" class="hidden">
                        <input type="date" class="bg-white border border-gray-300 text-blue-900 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <span class="mx-2 text-blue-900">à</span>
                        <input type="date" class="bg-white border border-gray-300 text-blue-900 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                    <button onclick="printPage()" class="bg-blue-800 hover:bg-blue-900 text-white px-4 py-2 rounded-lg transition-colors duration-300 flex items-center">
                        <i class="fas fa-print mr-2"></i> Imprimer
                    </button>
                </div>
            </div>
        </div>

        <!-- Main content container -->
        <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-6 max-w-4xl mx-auto transition-all duration-500 border-l-4 border-blue-500 print:shadow-none print:border-none">
            <!-- Header -->
            <div class="mb-6 text-center">
                <h1 class="text-xl md:text-2xl font-bold text-blue-900">Tableau de Statistiques par Site</h1>
                <p class="text-gray-700 mt-1">Vue d'ensemble du système Naftal</p>
            </div>

            <!-- General Diagram -->
            <div class="bg-white/70 rounded-lg shadow p-4 mb-8 border-l-4 border-blue-500">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Vue d'ensemble</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Users Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4">
                        <h4 class="font-bold text-blue-900 text-center mb-2">Utilisateurs</h4>
                        <canvas id="generalUsersChart"></canvas>
                    </div>
                    
                    <!-- Materials Card -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4">
                        <h4 class="font-bold text-blue-900 text-center mb-2">Matériels</h4>
                        <canvas id="generalMaterialsChart"></canvas>
                    </div>
                    
                    <!-- Reclamations Card -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4">
                        <h4 class="font-bold text-blue-900 text-center mb-2">Réclamations</h4>
                        <canvas id="generalReclamationsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Sites Statistics -->
            @foreach($sites as $site)
            <div class="mb-8 bg-white/70 rounded-lg shadow p-4 border-l-4 border-pink-500">
                <!-- Site Header -->
                <div class="bg-gradient-to-r from-pink-500 to-pink-600 rounded-t-lg -m-4 mb-4 p-4">
                    <h2 class="text-lg font-bold text-white">{{ $site->name }}</h2>
                </div>
                
                <!-- Stats Circles Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Users Circle -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow p-4 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-md font-semibold text-blue-900">Utilisateurs</h3>
                            <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="relative w-full h-40">
                            <canvas id="usersChart-{{ $site->id }}"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-2xl font-bold text-blue-900">{{ $site->users_count ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="mt-2 flex flex-col gap-1 text-xs text-blue-900">
                            <div class="bg-blue-100/50 p-1 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Admin: {{ $site->admins_count ?? 0 }}</p>
                            </div>
                            <div class="bg-blue-100/50 p-1 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Super Admin: {{ $site->superadmins_count ?? 0 }}</p>
                            </div>
                            <div class="bg-blue-100/50 p-1 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Leader: {{ $site->leaders_count ?? 0 }}</p>
                            </div>
                            <div class="bg-blue-100/50 p-1 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Utilisateur: {{ $site->utilisateurs_count ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Materials by Type Circle -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow p-4 border-l-4 border-green-500">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-md font-semibold text-blue-900">Matériels par Type</h3>
                            <div class="p-2 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-laptop"></i>
                            </div>
                        </div>
                        <div class="relative w-full h-40">
                            <canvas id="materialsTypeChart-{{ $site->id }}"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-2xl font-bold text-blue-900">{{ $site->computers_count + $site->printers_count + $site->ip_phones_count + $site->hotspots_count ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="mt-2 flex flex-col gap-1 text-xs text-blue-900">
                            <div class="bg-green-100/50 p-1 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Ordinateurs: {{ $site->computers_count ?? 0 }}</p>
                            </div>
                            <div class="bg-green-100/50 p-1 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Imprimantes: {{ $site->printers_count ?? 0 }}</p>
                            </div>
                            <div class="bg-green-100/50 p-1 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Téléphones: {{ $site->ip_phones_count ?? 0 }}</p>
                            </div>
                            <div class="bg-green-100/50 p-1 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Points d'accès: {{ $site->hotspots_count ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Materials State Circle -->
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg shadow p-4 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-md font-semibold text-blue-900">État des Matériels</h3>
                            <div class="p-2 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-cogs"></i>
                            </div>
                        </div>
                        <div class="relative w-full h-40">
                            <canvas id="materialsStateChart-{{ $site->id }}"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-2xl font-bold text-blue-900">{{ $site->computers_count + $site->printers_count + $site->ip_phones_count + $site->hotspots_count ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="mt-2 flex flex-col gap-1 text-xs text-blue-900">
                            <div class="bg-yellow-100/50 p-1 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Bon: {{ $site->bon_materials_count ?? 0 }}</p>
                            </div>
                            <div class="bg-yellow-100/50 p-1 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Défectueux: {{ $site->defectueux_materials_count ?? 0 }}</p>
                            </div>
                            <div class="bg-yellow-100/50 p-1 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Hors Service: {{ $site->hors_service_materials_count ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reclamations Circle -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg shadow p-4 border-l-4 border-purple-500">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-md font-semibold text-blue-900">Réclamations</h3>
                            <div class="p-2 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                        </div>
                        <div class="relative w-full h-40">
                            <canvas id="reclamationsChart-{{ $site->id }}"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-2xl font-bold text-blue-900">{{ $site->reclamations_count ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="mt-2 flex flex-col gap-1 text-xs text-blue-900">
                            <div class="bg-purple-100/50 p-1 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Nouvelles: {{ $site->new_reclamations_count ?? 0 }}</p>
                            </div>
                            <div class="bg-purple-100/50 p-1 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">En cours: {{ $site->en_cours_reclamations_count ?? 0 }}</p>
                            </div>
                            <div class="bg-purple-100/50 p-1 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Traitées: {{ $site->traitee_reclamations_count ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Location Types Card -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg shadow p-4 border-l-4 border-gray-500">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-md font-semibold text-blue-900">Locations: {{ $site->locations_count ?? 0 }}</h3>
                        <div class="p-2 rounded-full bg-gray-100 text-gray-600">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($site->location_types as $type => $count)
                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm whitespace-nowrap">
                            {{ $type }}: {{ $count }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Big Diagram Section -->
            <div class="bg-white/70 rounded-lg shadow p-6 mb-8 border-l-4 border-blue-500">
                <h2 class="text-xl font-bold text-blue-900 mb-6 text-center">Synthèse Globale</h2>
                
                <!-- First Row - Main Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Total Users -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-blue-900">Utilisateurs Totaux</h3>
                                <p class="text-3xl font-bold text-blue-900">{{ $userCount }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-col gap-2">
                            <div class="bg-blue-100/50 p-2 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Admin: {{ $adminCount }}</p>
                            </div>
                            <div class="bg-blue-100/50 p-2 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Super Admin: {{ $superadminCount }}</p>
                            </div>
                            <div class="bg-blue-100/50 p-2 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Leader: {{ $leaderCount }}</p>
                            </div>
                            <div class="bg-blue-100/50 p-2 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Utilisateur: {{ $utilisateurCount }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Materials -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-blue-900">Matériels Totaux</h3>
                                <p class="text-3xl font-bold text-blue-900">{{ $materialCount }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-laptop text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-col gap-2">
                            <div class="bg-green-100/50 p-2 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Ordinateurs: {{ $computerCount }}</p>
                            </div>
                            <div class="bg-green-100/50 p-2 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Imprimantes: {{ $printerCount }}</p>
                            </div>
                            <div class="bg-green-100/50 p-2 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Téléphones: {{ $ipPhoneCount }}</p>
                            </div>
                            <div class="bg-green-100/50 p-2 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Points d'accès: {{ $hotspotCount }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Material States -->
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-blue-900">État des Matériels</h3>
                                <p class="text-3xl font-bold text-blue-900">{{ $materialCount }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-cogs text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-col gap-2">
                            <div class="bg-yellow-100/50 p-2 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Bon: {{ $materialBon }}</p>
                            </div>
                            <div class="bg-yellow-100/50 p-2 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Défectueux: {{ $materialDefectueux }}</p>
                            </div>
                            <div class="bg-yellow-100/50 p-2 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Hors Service: {{ $materialHorsService }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Second Row - Reclamations and Sites -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Reclamations -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border-l-4 border-purple-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-blue-900">Réclamations</h3>
                                <p class="text-3xl font-bold text-blue-900">{{ $reclamationCount }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-exclamation-circle text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-col gap-2">
                            <div class="bg-purple-100/50 p-2 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Nouvelles: {{ $newReclamationCount }}</p>
                            </div>
                            <div class="bg-purple-100/50 p-2 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">En cours: {{ $enCoursReclamationCount }}</p>
                            </div>
                            <div class="bg-purple-100/50 p-2 rounded text-center">
                                <p class="font-semibold whitespace-nowrap">Traitées: {{ $traiteeReclamationCount }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <canvas id="bigReclamationsChart" height="150"></canvas>
                        </div>
                    </div>
                    
                    <!-- Sites and Locations -->
                    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-4 border-l-4 border-indigo-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-blue-900">Sites & Locations</h3>
                                <p class="text-3xl font-bold text-blue-900">{{ $siteCount }} Sites</p>
                            </div>
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                                <i class="fas fa-building text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-lg font-semibold text-blue-900">{{ $locationCount }} Locations au total</p>
                            <div class="mt-2">
                                <canvas id="bigSitesChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Third Row - Materials Distribution -->
                <div class="mt-8 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border-l-4 border-gray-500">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4 text-center">Distribution des Matériels par Site</h3>
                    <div class="h-64">
                        <canvas id="bigMaterialsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Time period selector logic
        const timePeriodSelect = document.getElementById('timePeriod');
        const customDateRange = document.getElementById('customDateRange');
        
        timePeriodSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDateRange.classList.remove('hidden');
            } else {
                customDateRange.classList.add('hidden');
            }
        });

        // Print function
        window.printPage = function() {
            window.print();
        };

        // General Users Chart
        new Chart(document.getElementById('generalUsersChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(@json($generalDiagramData['users'])),
                datasets: [{
                    data: Object.values(@json($generalDiagramData['users'])),
                    backgroundColor: ['#4CAF50', '#2196F3', '#FFC107', '#9C27B0'],
                    borderWidth: 1
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // General Materials Chart
        new Chart(document.getElementById('generalMaterialsChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(@json($generalDiagramData['materials'])),
                datasets: [{
                    data: Object.values(@json($generalDiagramData['materials'])),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                    borderWidth: 1
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // General Reclamations Chart
        new Chart(document.getElementById('generalReclamationsChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Nouvelles', 'En cours', 'Traitées'],
                datasets: [{
                    data: [
                        {{ $newReclamationCount }},
                        {{ $enCoursReclamationCount }},
                        {{ $traiteeReclamationCount }}
                    ],
                    backgroundColor: ['#E91E63', '#3F51B5', '#8BC34A'],
                    borderWidth: 1
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Initialize charts for each site
        @foreach($sites as $site)
            // Users Chart
            const usersCtx{{ $site->id }} = document.getElementById('usersChart-{{ $site->id }}').getContext('2d');
            new Chart(usersCtx{{ $site->id }}, {
                type: 'doughnut',
                data: {
                    labels: ['Admin', 'SuperAdmin', 'Leader', 'Utilisateur'],
                    datasets: [{
                        data: [
                            {{ $site->admins_count ?? 0 }}, 
                            {{ $site->superadmins_count ?? 0 }}, 
                            {{ $site->leaders_count ?? 0 }},
                            {{ $site->utilisateurs_count ?? 0 }}
                        ],
                        backgroundColor: ['#4CAF50', '#2196F3', '#FFC107', '#9C27B0'],
                        borderWidth: 1
                    }]
                },
                options: {
                    cutout: '75%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Materials by Type Chart
            const materialsTypeCtx{{ $site->id }} = document.getElementById('materialsTypeChart-{{ $site->id }}').getContext('2d');
            new Chart(materialsTypeCtx{{ $site->id }}, {
                type: 'doughnut',
                data: {
                    labels: ['Ordinateurs', 'Imprimantes', 'Téléphones', 'Hotspots'],
                    datasets: [{
                        data: [
                            {{ $site->computers_count ?? 0 }}, 
                            {{ $site->printers_count ?? 0 }}, 
                            {{ $site->ip_phones_count ?? 0 }},
                            {{ $site->hotspots_count ?? 0 }}
                        ],
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                        borderWidth: 1
                    }]
                },
                options: {
                    cutout: '75%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Materials State Chart
            const materialsStateCtx{{ $site->id }} = document.getElementById('materialsStateChart-{{ $site->id }}').getContext('2d');
            new Chart(materialsStateCtx{{ $site->id }}, {
                type: 'doughnut',
                data: {
                    labels: ['Bon', 'Défectueux', 'Hors Service'],
                    datasets: [{
                        data: [
                            {{ $site->bon_materials_count ?? 0 }}, 
                            {{ $site->defectueux_materials_count ?? 0 }}, 
                            {{ $site->hors_service_materials_count ?? 0 }}
                        ],
                        backgroundColor: ['#4CAF50', '#F44336', '#FFC107'],
                        borderWidth: 1
                    }]
                },
                options: {
                    cutout: '75%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Reclamations Chart
            const reclamationsCtx{{ $site->id }} = document.getElementById('reclamationsChart-{{ $site->id }}').getContext('2d');
            new Chart(reclamationsCtx{{ $site->id }}, {
                type: 'doughnut',
                data: {
                    labels: ['Nouvelles', 'En cours', 'Traitées'],
                    datasets: [{
                        data: [
                            {{ $site->new_reclamations_count ?? 0 }}, 
                            {{ $site->en_cours_reclamations_count ?? 0 }}, 
                            {{ $site->traitee_reclamations_count ?? 0 }}
                        ],
                        backgroundColor: ['#FF6384', '#36A2EB', '#4CAF50'],
                        borderWidth: 1
                    }]
                },
                options: {
                    cutout: '75%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        @endforeach

        // Big Reclamations Chart
        new Chart(document.getElementById('bigReclamationsChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Nouvelles', 'En cours', 'Traitées'],
                datasets: [{
                    label: 'Réclamations',
                    data: [
                        {{ $newReclamationCount }},
                        {{ $enCoursReclamationCount }},
                        {{ $traiteeReclamationCount }}
                    ],
                    backgroundColor: [
                        '#E91E63',
                        '#3F51B5',
                        '#8BC34A'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Big Sites Chart
        new Chart(document.getElementById('bigSitesChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($sites->pluck('name')),
                datasets: [{
                    data: @json($sites->pluck('locations_count')),
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                        '#9966FF', '#FF9F40', '#8AC249', '#EA5F89',
                        '#00BFFF', '#FFD700', '#32CD32', '#9370DB'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                },
                cutout: '60%'
            }
        });

        // Big Materials Chart
        const materialsData = {
            labels: @json($sites->pluck('name')),
            datasets: [
                {
                    label: 'Ordinateurs',
                    data: @json($sites->pluck('computers_count')),
                    backgroundColor: '#FF6384',
                    borderWidth: 1
                },
                {
                    label: 'Imprimantes',
                    data: @json($sites->pluck('printers_count')),
                    backgroundColor: '#36A2EB',
                    borderWidth: 1
                },
                {
                    label: 'Téléphones',
                    data: @json($sites->pluck('ip_phones_count')),
                    backgroundColor: '#FFCE56',
                    borderWidth: 1
                },
                {
                    label: 'Hotspots',
                    data: @json($sites->pluck('hotspots_count')),
                    backgroundColor: '#4BC0C0',
                    borderWidth: 1
                }
            ]
        };

        new Chart(document.getElementById('bigMaterialsChart').getContext('2d'), {
            type: 'bar',
            data: materialsData,
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .relative, .relative * {
            visibility: visible;
        }
        .relative {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            background: white !important;
        }
        .fixed {
            display: none !important;
        }
        .backdrop-blur-lg, .backdrop-blur-sm {
            backdrop-filter: none !important;
            background: white !important;
        }
        .bg-white {
            background: white !important;
        }
        .shadow-2xl, .shadow-lg, .shadow-xl {
            box-shadow: none !important;
        }
        .border, .border-l-4 {
            border: 1px solid #ddd !important;
        }
        .bg-gradient-to-br {
            background: white !important;
        }
    }
    .whitespace-nowrap {
        white-space: nowrap;
    }
</style>
@endsection