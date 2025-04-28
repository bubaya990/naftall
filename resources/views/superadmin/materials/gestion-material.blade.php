@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-6 pb-16">
        <!-- Dashboard content with glassmorphism effect -->
        <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-8 max-w-7xl mx-auto mt-8 transition-all duration-500 transform hover:scale-[1.01]">
            <!-- Header -->
            <div class="mb-8 text-center">
                <div class="animate-slideInLeft">
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-900">Inventaire des Matériels</h1>
                    <p class="text-yellow-600 mt-2">Vue d'ensemble de tous les équipements</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                <!-- Computers Card -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 backdrop-blur-sm rounded-xl shadow-lg p-6 border-l-4 border-blue-500 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-blue-900 mb-1">Ordinateurs</h2>
                            <p class="text-3xl font-bold text-blue-900 animate-count" data-target="{{ $totalComputers }}">0</p>
                        </div>
                        <div class="p-3 rounded-full bg-blue-100/80 text-blue-600 transform hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-laptop"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        @foreach($siteMaterialCounts as $site)
                            <div class="flex justify-between text-sm text-blue-900 py-2 border-b border-blue-200/50">
                                <span>Site {{ $site['site_name'] }}</span>
                                <span class="font-medium">{{ $site['computers'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('superadmin.materials.list', 'computers') }}" class="mt-4 inline-block w-full text-center py-2 bg-blue-500/10 hover:bg-blue-500/20 text-blue-700 rounded-lg transition-all duration-200 transform hover:scale-[1.02]">
                        <span class="inline-flex items-center justify-center">
                            Voir la liste <i class="fas fa-chevron-right ml-2"></i>
                        </span>
                    </a>
                </div>

                <!-- Printers Card -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 backdrop-blur-sm rounded-xl shadow-lg p-6 border-l-4 border-green-500 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-blue-900 mb-1">Imprimantes</h2>
                            <p class="text-3xl font-bold text-blue-900 animate-count" data-target="{{ $totalPrinters }}">0</p>
                        </div>
                        <div class="p-3 rounded-full bg-green-100/80 text-green-600 transform hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-print"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        @foreach($siteMaterialCounts as $site)
                            <div class="flex justify-between text-sm text-blue-900 py-2 border-b border-green-200/50">
                                <span>Site {{ $site['site_name'] }}</span>
                                <span class="font-medium">{{ $site['printers'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('superadmin.materials.list', 'printers') }}" class="mt-4 inline-block w-full text-center py-2 bg-green-500/10 hover:bg-green-500/20 text-green-700 rounded-lg transition-all duration-200 transform hover:scale-[1.02]">
                        <span class="inline-flex items-center justify-center">
                            Voir la liste <i class="fas fa-chevron-right ml-2"></i>
                        </span>
                    </a>
                </div>

                <!-- IP Phones Card -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 backdrop-blur-sm rounded-xl shadow-lg p-6 border-l-4 border-purple-500 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-blue-900 mb-1">Téléphones IP</h2>
                            <p class="text-3xl font-bold text-blue-900 animate-count" data-target="{{ $totalIpPhones }}">0</p>
                        </div>
                        <div class="p-3 rounded-full bg-purple-100/80 text-purple-600 transform hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        @foreach($siteMaterialCounts as $site)
                            <div class="flex justify-between text-sm text-blue-900 py-2 border-b border-purple-200/50">
                                <span>Site {{ $site['site_name'] }}</span>
                                <span class="font-medium">{{ $site['ipPhones'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('superadmin.materials.list', 'ip-phones') }}" class="mt-4 inline-block w-full text-center py-2 bg-purple-500/10 hover:bg-purple-500/20 text-purple-700 rounded-lg transition-all duration-200 transform hover:scale-[1.02]">
                        <span class="inline-flex items-center justify-center">
                            Voir la liste <i class="fas fa-chevron-right ml-2"></i>
                        </span>
                    </a>
                </div>

                <!-- Hotspots Card -->
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 backdrop-blur-sm rounded-xl shadow-lg p-6 border-l-4 border-yellow-500 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-blue-900 mb-1">Hotspots</h2>
                            <p class="text-3xl font-bold text-blue-900 animate-count" data-target="{{ $totalHotspots }}">0</p>
                        </div>
                        <div class="p-3 rounded-full bg-yellow-100/80 text-yellow-600 transform hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-wifi"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        @foreach($siteMaterialCounts as $site)
                            <div class="flex justify-between text-sm text-blue-900 py-2 border-b border-yellow-200/50">
                                <span>Site {{ $site['site_name'] }}</span>
                                <span class="font-medium">{{ $site['hotspots'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('superadmin.materials.list', 'hotspots') }}" class="mt-4 inline-block w-full text-center py-2 bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-700 rounded-lg transition-all duration-200 transform hover:scale-[1.02]">
                        <span class="inline-flex items-center justify-center">
                            Voir la liste <i class="fas fa-chevron-right ml-2"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

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
    });
</script>
@endsection