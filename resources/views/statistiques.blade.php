@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Blurred background -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

    <div class="relative z-10 min-h-screen p-4 pb-12">
        <!-- Main content container with nude weight background -->
        <div class="bg-amber-50/80 backdrop-blur-lg shadow-2xl rounded-2xl p-6 max-w-4xl mx-auto mt-6 transition-all duration-500 transform hover:scale-[1.01] border border-amber-100">
            <!-- Header -->
            <div class="mb-6 text-center animate-slideInLeft">
                <h1 class="text-xl md:text-2xl font-bold text-blue-900">Tableau de Statistiques</h1>
                <p class="text-amber-700 mt-1">Vue d'ensemble du système Naftal</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <!-- Users Card -->
                <div class="bg-gradient-to-br from-pink-50 to-pink-100 backdrop-blur-sm rounded-xl shadow-lg p-4 border-l-4 border-pink-500 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-blue-900 mb-1">Utilisateurs</h2>
                            <p class="text-2xl font-bold text-blue-900 animate-count" data-target="{{ $userCount }}">0</p>
                        </div>
                        <div class="p-2 rounded-full bg-pink-100/80 text-pink-600 transform hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-users text-lg"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="flex justify-between text-xs text-blue-900 py-1 border-b border-pink-200/50">
                            <span>Admins</span>
                            <span class="font-medium">{{ $adminCount }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-blue-900 py-1 border-b border-pink-200/50">
                            <span>Leaders</span>
                            <span class="font-medium">{{ $leaderCount }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-blue-900 py-1">
                            <span>Utilisateurs</span>
                            <span class="font-medium">{{ $utilisateurCount }}</span>
                        </div>
                    </div>
                </div>

                <!-- Materials Card -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 backdrop-blur-sm rounded-xl shadow-lg p-4 border-l-4 border-blue-500 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-blue-900 mb-1">Matériels</h2>
                            <p class="text-2xl font-bold text-blue-900 animate-count" data-target="{{ $materialCount }}">0</p>
                        </div>
                        <div class="p-2 rounded-full bg-blue-100/80 text-blue-600 transform hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-boxes text-lg"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="flex justify-between text-xs text-blue-900 py-1 border-b border-blue-200/50">
                            <span>Ordinateurs</span>
                            <span class="font-medium">{{ $computerCount }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-blue-900 py-1 border-b border-blue-200/50">
                            <span>Imprimantes</span>
                            <span class="font-medium">{{ $printerCount }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-blue-900 py-1">
                            <span>Fonctionnels</span>
                            <span class="font-medium">{{ $materialFonctionnel }}</span>
                        </div>
                    </div>
                </div>

                <!-- Reclamations Card - Green version -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 backdrop-blur-sm rounded-xl shadow-lg p-4 border-l-4 border-green-500 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-blue-900 mb-1">Réclamations</h2>
                            <p class="text-2xl font-bold text-blue-900 animate-count" data-target="{{ $reclamationCount }}">0</p>
                        </div>
                        <div class="p-2 rounded-full bg-green-100/80 text-green-600 transform hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-exclamation-circle text-lg"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="flex justify-between text-xs text-blue-900 py-1 border-b border-green-200/50">
                            <span>Nouvelles</span>
                            <span class="font-medium">{{ $newReclamationCount }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-blue-900 py-1 border-b border-green-200/50">
                            <span>En cours</span>
                            <span class="font-medium">{{ $reclamationStatusCounts['in_progress'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-blue-900 py-1">
                            <span>Résolues</span>
                            <span class="font-medium">{{ $resolvedReclamationCount }}</span>
                        </div>
                    </div>
                </div>

                <!-- Sites Card -->
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 backdrop-blur-sm rounded-xl shadow-lg p-4 border-l-4 border-yellow-500 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-blue-900 mb-1">Sites</h2>
                            <p class="text-2xl font-bold text-blue-900 animate-count" data-target="{{ $siteCount }}">0</p>
                        </div>
                        <div class="p-2 rounded-full bg-yellow-100/80 text-yellow-600 transform hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-map-marker-alt text-lg"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="flex justify-between text-xs text-blue-900 py-1 border-b border-yellow-200/50">
                            <span>Branches</span>
                            <span class="font-medium">{{ $brancheCount }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-blue-900 py-1 border-b border-yellow-200/50">
                            <span>Locations</span>
                            <span class="font-medium">{{ $locationCount }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-blue-900 py-1">
                            <span>Réclamations</span>
                            <span class="font-medium">{{ $sitesWithReclamations->sum('reclamations_count') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="mt-6 grid grid-cols-1 gap-4">
                <!-- Reclamations Chart - Green version -->
                <div class="bg-white/90 backdrop-blur-sm rounded-xl shadow-lg p-4 border border-amber-200">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3">Statut des Réclamations</h3>
                    <div class="flex justify-center">
                        <div style="width: 250px; height: 250px;">
                            <canvas id="reclamationStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Materials Chart -->
                <div class="bg-white/90 backdrop-blur-sm rounded-xl shadow-lg p-4 border border-amber-200">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3">Types de Matériels</h3>
                    <div class="flex justify-center">
                        <div style="width: 250px; height: 250px;">
                            <canvas id="materialTypeChart"></canvas>
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
        // Animate counting numbers
        const counters = document.querySelectorAll('.animate-count');
        const speed = 200;
        
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const increment = target / speed;
            
            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(updateCount, 1);
            } else {
                counter.innerText = target;
            }
            
            function updateCount() {
                const count = +counter.innerText;
                const increment = target / speed;
                
                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(updateCount, 1);
                } else {
                    counter.innerText = target;
                }
            }
        });

        // Reclamation Status Chart - Green version
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
                        ['#4CAF50', '#8BC34A', '#CDDC39', '#FFEB3B'] : // Green color palette
                        ['#e5e7eb'],
                    borderWidth: 3, // Thicker border
                    borderColor: '#fff',
                    weight: 2 // Heavier weight for segments
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%', // Slightly smaller hole for more visible segments
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            color: '#374151',
                            font: {
                                weight: 'bold' // Bold legend text
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                elements: {
                    arc: {
                        borderWidth: 2,
                        borderColor: '#fff',
                        weight: 2 // Heavier weight for segments
                    }
                }
            }
        });

        // Material Type Chart with heavier segments
        const materialCtx = document.getElementById('materialTypeChart').getContext('2d');
        const materialData = {
            computers: {{ $computerCount }},
            printers: {{ $printerCount }},
            ipPhones: {{ $ipPhoneCount ?? 0 }},
            hotspots: {{ $hotspotCount ?? 0 }}
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
                    borderWidth: 3, // Thicker border
                    borderColor: '#fff',
                    weight: 2 // Heavier weight for segments
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%', // Slightly smaller hole for more visible segments
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            color: '#374151',
                            font: {
                                weight: 'bold' // Bold legend text
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                elements: {
                    arc: {
                        borderWidth: 2,
                        borderColor: '#fff',
                        weight: 2 // Heavier weight for segments
                    }
                }
            }
        });
    });
</script>
@endsection