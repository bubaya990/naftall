@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Blurred background -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

    <div class="relative z-10 min-h-screen p-6 pb-16">
        <!-- Header with blurred background -->
        <div class="relative rounded-2xl overflow-hidden mb-6 h-32 flex items-center justify-center">
            <!-- Blurred header background -->
            <div class="absolute inset-0 bg-cover bg-center filter blur-md" style="background-image: url('/image/background.jpg');"></div>
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/30"></div>
            <!-- Header content -->
            <h1 class="relative z-10 text-3xl md:text-4xl font-bold text-white text-center px-4">
                Tableau de Statistiques – <span class="text-yellow-400">Naftal</span>
            </h1>
        </div>

        <!-- Main content container -->
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Aperçu Général Section -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-semibold text-gray-800">Aperçu Général</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Users Card with pink shadow -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 transition-all duration-300 transform hover:scale-105 shadow-[0_10px_20px_-5px_rgba(255,99,132,0.3)] hover:shadow-[0_15px_25px_-5px_rgba(255,99,132,0.4)]">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Utilisateurs</p>
                                    <h3 class="text-2xl font-bold text-gray-800">{{ $userCount }}</h3>
                                </div>
                                <div class="bg-pink-100 text-pink-600 p-3 rounded-full">
                                    <i class="fas fa-users text-xl"></i>
                                </div>
                            </div>
                            <div class="mt-4 text-sm text-gray-500">
                                <div class="flex justify-between">
                                    <span>Admins</span>
                                    <span class="font-medium">{{ $adminCount }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Leaders</span>
                                    <span class="font-medium">{{ $leaderCount }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Materials Card with blue shadow -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 transition-all duration-300 transform hover:scale-105 shadow-[0_10px_20px_-5px_rgba(54,162,235,0.3)] hover:shadow-[0_15px_25px_-5px_rgba(54,162,235,0.4)]">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Matériels</p>
                                    <h3 class="text-2xl font-bold text-gray-800">{{ $materialCount }}</h3>
                                </div>
                                <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                                    <i class="fas fa-boxes text-xl"></i>
                                </div>
                            </div>
                            <div class="mt-4 text-sm text-gray-500">
                                <div class="flex justify-between">
                                    <span>Ordinateurs</span>
                                    <span class="font-medium">{{ $computerCount }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Imprimantes</span>
                                    <span class="font-medium">{{ $printerCount }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Reclamations Card with teal shadow -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 transition-all duration-300 transform hover:scale-105 shadow-[0_10px_20px_-5px_rgba(75,192,192,0.3)] hover:shadow-[0_15px_25px_-5px_rgba(75,192,192,0.4)]">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Réclamations</p>
                                    <h3 class="text-2xl font-bold text-gray-800">{{ $reclamationCount }}</h3>
                                </div>
                                <div class="bg-teal-100 text-teal-600 p-3 rounded-full">
                                    <i class="fas fa-exclamation-circle text-xl"></i>
                                </div>
                            </div>
                            <div class="mt-4 text-sm text-gray-500">
                                <div class="flex justify-between">
                                    <span>Nouvelles</span>
                                    <span class="font-medium">{{ $newReclamationCount }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Résolues</span>
                                    <span class="font-medium">{{ $resolvedReclamationCount }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Sites Card with yellow shadow -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 transition-all duration-300 transform hover:scale-105 shadow-[0_10px_20px_-5px_rgba(255,206,86,0.3)] hover:shadow-[0_15px_25px_-5px_rgba(255,206,86,0.4)]">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Sites</p>
                                    <h3 class="text-2xl font-bold text-gray-800">{{ $siteCount }}</h3>
                                </div>
                                <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full">
                                    <i class="fas fa-map-marker-alt text-xl"></i>
                                </div>
                            </div>
                            <div class="mt-4 text-sm text-gray-500">
                                <div class="flex justify-between">
                                    <span>Branches</span>
                                    <span class="font-medium">{{ $brancheCount }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Locations</span>
                                    <span class="font-medium">{{ $locationCount }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visualisation des Données Section -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-semibold text-gray-800">Visualisation des Données</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Reclamations Chart -->
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-[0_10px_25px_-5px_rgba(255,99,132,0.1)]">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Statut des Réclamations</h3>
                            <div class="flex justify-center">
                                <div style="width: 250px; height: 250px;">
                                    <canvas id="reclamationStatusChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Materials Chart -->
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-[0_10px_25px_-5px_rgba(54,162,235,0.1)]">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Types de Matériels</h3>
                            <div class="flex justify-center">
                                <div style="width: 250px; height: 250px;">
                                    <canvas id="materialTypeChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails Statistiques Section -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-semibold text-gray-800">Détails Statistiques</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Users by Role -->
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-[0_10px_25px_-5px_rgba(255,99,132,0.1)]">
                            <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-user-tag mr-2 text-yellow-600"></i> Utilisateurs par Rôle
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-pink-500 mr-2"></span>
                                        Super Admin
                                    </span>
                                    <span class="font-medium bg-pink-100 text-pink-800 px-2 py-1 rounded-full text-sm">{{ $superadminCount }}</span>
                                </li>
                                <li class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                                        Admins
                                    </span>
                                    <span class="font-medium bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">{{ $adminCount }}</span>
                                </li>
                                <li class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-teal-500 mr-2"></span>
                                        Leaders
                                    </span>
                                    <span class="font-medium bg-teal-100 text-teal-800 px-2 py-1 rounded-full text-sm">{{ $leaderCount }}</span>
                                </li>
                                <li class="flex justify-between items-center py-2">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                                        Utilisateurs
                                    </span>
                                    <span class="font-medium bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm">{{ $utilisateurCount }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Materials by State -->
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-[0_10px_25px_-5px_rgba(75,192,192,0.1)]">
                            <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-cogs mr-2 text-yellow-600"></i> État des Matériels
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                                        Fonctionnel
                                    </span>
                                    <span class="font-medium bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">{{ $materialFonctionnel }}</span>
                                </li>
                                <li class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                                        En panne
                                    </span>
                                    <span class="font-medium bg-red-100 text-red-800 px-2 py-1 rounded-full text-sm">{{ $materialPanne }}</span>
                                </li>
                                <li class="flex justify-between items-center py-2">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                                        En maintenance
                                    </span>
                                    <span class="font-medium bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm">{{ $materialMaintenance }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Reclamations by Site -->
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-[0_10px_25px_-5px_rgba(255,206,86,0.1)]">
                            <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-map-marked-alt mr-2 text-yellow-600"></i> Réclamations par Site
                            </h4>
                            <div class="max-h-64 overflow-y-auto pr-2">
                                <ul class="space-y-3">
                                    @foreach($sitesWithReclamations as $site)
                                    <li class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="flex items-center truncate">
                                            <span class="w-3 h-3 rounded-full bg-indigo-500 mr-2"></span>
                                            <span class="truncate">{{ $site->name }}</span>
                                        </span>
                                        <span class="font-medium bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full text-sm">{{ $site->reclamations_count }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
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
        // Reclamation Status Chart with empty state handling
        const reclamationCtx = document.getElementById('reclamationStatusChart').getContext('2d');
        const reclamationData = {
            new: {{ $reclamationStatusCounts['new'] ?? 0 }},
            in_progress: {{ $reclamationStatusCounts['in_progress'] ?? 0 }},
            resolved: {{ $reclamationStatusCounts['resolved'] ?? 0 }},
            closed: {{ $reclamationStatusCounts['closed'] ?? 0 }}
        };
        
        const hasReclamationData = Object.values(reclamationData).some(val => val > 0);
        
        new Chart(reclamationCtx, {
            type: 'doughnut',
            data: {
                labels: ['Nouveau', 'En cours', 'Résolu', 'Fermé'],
                datasets: [{
                    data: hasReclamationData ? 
                        [reclamationData.new, reclamationData.in_progress, reclamationData.resolved, reclamationData.closed] : 
                        [1],
                    backgroundColor: hasReclamationData ? 
                        ['#FF6384', '#36A2EB', '#4BC0C0', '#FFCE56'] : 
                        ['#e5e7eb'],
                    hoverBackgroundColor: hasReclamationData ? 
                        ['#FF6384', '#36A2EB', '#4BC0C0', '#FFCE56'] : 
                        ['#e5e7eb'],
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            color: '#374151'
                        }
                    },
                    tooltip: {
                        enabled: hasReclamationData,
                        backgroundColor: '#1f2937',
                        titleColor: '#f9fafb',
                        bodyColor: '#f9fafb',
                        borderColor: '#4b5563',
                        borderWidth: 1
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });

        // Material Type Chart with empty state handling
        const materialCtx = document.getElementById('materialTypeChart').getContext('2d');
        const materialData = {
            computers: {{ $computerCount }},
            printers: {{ $printerCount }},
            ipPhones: {{ $ipPhoneCount }},
            hotspots: {{ $hotspotCount }}
        };
        
        const hasMaterialData = Object.values(materialData).some(val => val > 0);
        
        new Chart(materialCtx, {
            type: 'doughnut',
            data: {
                labels: ['Ordinateurs', 'Imprimantes', 'Téléphones IP', 'Hotspots'],
                datasets: [{
                    data: hasMaterialData ? 
                        [materialData.computers, materialData.printers, materialData.ipPhones, materialData.hotspots] : 
                        [1],
                    backgroundColor: hasMaterialData ? 
                        ['#FF6384', '#36A2EB', '#4BC0C0', '#FFCE56'] : 
                        ['#e5e7eb'],
                    hoverBackgroundColor: hasMaterialData ? 
                        ['#FF6384', '#36A2EB', '#4BC0C0', '#FFCE56'] : 
                        ['#e5e7eb'],
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            color: '#374151'
                        }
                    },
                    tooltip: {
                        enabled: hasMaterialData,
                        backgroundColor: '#1f2937',
                        titleColor: '#f9fafb',
                        bodyColor: '#f9fafb',
                        borderColor: '#4b5563',
                        borderWidth: 1
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    });
</script>
@endsection