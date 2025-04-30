@extends('layouts.app')

@section('content')
@php
    // Set default values if variables are not defined
    $userCount = $userCount ?? 0;
    $materialCount = $materialCount ?? 0;
    $locationCount = $locationCount ?? 0;
    $userGrowth = $userGrowth ?? 0;
    $materialGrowth = $materialGrowth ?? 0;
    $newLocations = $newLocations ?? 0;
    
    // Get unread messages count
    $unreadCount = auth()->user()->unreadMessages()->count();
    // Get latest 3 reclamations
    $latestReclamations = $latestReclamations ?? collect();
@endphp

<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>
    
    <!-- Notification bell + dropdown wrapper -->
    <div class="fixed top-4 right-10 z-20 animate-slideInRight">
        <div class="relative inline-block">
            <!-- Notification Bell -->
            <button id="notification-btn" class="relative p-3 rounded-full hover:bg-white/30 text-gray-700 hover:text-blue-900 transition-all duration-300 transform hover:rotate-12 bg-white/80 backdrop-blur-sm shadow-lg">
                <i class="fas fa-bell text-xl"></i>
                @if($unreadCount > 0)
                    <span class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-red-500 text-white text-xs flex items-center justify-center animate-bounce">
                        {{ $unreadCount }}
                    </span>
                @endif
            </button>

            <!-- Notification dropdown -->
            <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-72 bg-white backdrop-blur-lg rounded-xl shadow-xl z-10 border border-gray-200/50 transform origin-top transition-all duration-300 scale-95 opacity-0">
                <div class="p-3 border-b border-gray-200/50 bg-white/80 flex justify-between items-center">
                    <h3 class="font-medium text-blue-900">Notifications ({{ $unreadCount }})</h3>
                    <a href="{{ route('superadmin.reclamations.addreclamation') }}" class="text-xs text-yellow-600 hover:text-yellow-700">Ajouter une réclamation</a>
                </div>

                <div class="max-h-60 overflow-y-auto">
                    @forelse($latestReclamations as $reclamation)
                        <a href="#" class="block px-4 py-3 hover:bg-white/50 border-b border-gray-200/50 transition-all duration-200 hover:pl-5">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 text-yellow-500 mt-1 animate-pulse">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-blue-900">Reclamation #{{ $reclamation->num_R }}</p>
                                    <p class="text-xs text-gray-600 truncate">{{ Str::limit($reclamation->message, 30) }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $reclamation->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-4 py-3 text-center text-gray-500">Aucune notification</div>
                    @endforelse
                </div>

                <div class="p-2 border-t border-gray-200/50 bg-white/50 text-center">
                    <a href="{{ route('superadmin.reclamations') }}" class="text-xs font-medium text-yellow-600 hover:text-yellow-700 transition-colors duration-200">Voir toutes les reclamations</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-6 pb-16">
        <!-- Dashboard content with glassmorphism effect -->
        <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-8 max-w-7xl mx-auto mt-8 transition-all duration-500 transform hover:scale-[1.01]">
            <!-- Header -->
            <div class="mb-8">
                <div class="animate-slideInLeft">
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-900">Bienvenue, <span class="text-yellow-600">{{ Auth::user()->name }}</span></h1>
                    <p class="text-gray-600 mt-1">Tableau de bord Super Admin</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Users Card -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 backdrop-blur-sm rounded-xl shadow-lg p-6 border-l-4 border-blue-500 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-900">Utilisateurs</p>
                            <p class="text-2xl font-bold text-blue-900 animate-count" data-target="{{ $userCount }}">0</p>
                        </div>
                        <div class="p-3 rounded-full bg-blue-100/80 text-blue-600 transform hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-700 mt-2">
                        <span class="{{ $userGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                            {{ $userGrowth >= 0 ? '+' : '' }}{{ $userGrowth }}%
                        </span> ce mois
                    </p>
                    <div class="mt-2 h-1 bg-gray-200/50 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full animate-progress" style="width: {{ min(abs($userGrowth), 100) }}%"></div>
                    </div>
                </div>
                
                <!-- Materials Card -->
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 backdrop-blur-sm rounded-xl shadow-lg p-6 border-l-4 border-yellow-500 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-900">Matériels</p>
                            <p class="text-2xl font-bold text-blue-900 animate-count" data-target="{{ $materialCount }}">0</p>
                        </div>
                        <div class="p-3 rounded-full bg-yellow-100/80 text-yellow-600 transform hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-laptop"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-700 mt-2">
                        <span class="{{ $materialGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                            {{ $materialGrowth >= 0 ? '+' : '' }}{{ $materialGrowth }}%
                        </span> ce mois
                    </p>
                    <div class="mt-2 h-1 bg-gray-200/50 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full animate-progress" style="width: {{ min(abs($materialGrowth), 100) }}%"></div>
                    </div>
                </div>
                
                <!-- Locations Card -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 backdrop-blur-sm rounded-xl shadow-lg p-6 border-l-4 border-green-500 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-900">Localités</p>
                            <p class="text-2xl font-bold text-blue-900 animate-count" data-target="{{ $locationCount }}">0</p>
                        </div>
                        <div class="p-3 rounded-full bg-green-100/80 text-green-600 transform hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-700 mt-2">
                        <span class="{{ $newLocations > 0 ? 'text-green-600' : 'text-gray-600' }} font-medium">
                            {{ $newLocations > 0 ? '+' : '' }}{{ $newLocations }}
                        </span> nouvelles
                    </p>
                    <div class="mt-2 h-1 bg-gray-200/50 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full animate-progress" style="width: {{ $newLocations > 0 ? '40%' : '10%' }}"></div>
                    </div>
                </div>
            </div>

            <!-- COM & CBR Buttons Section -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg overflow-hidden mb-8 transition-all duration-300 hover:shadow-xl">
                <div class="p-4 border-b border-gray-200/50 bg-white/50">
                    <h2 class="font-semibold text-blue-900 text-center text-lg">Accès Rapide</h2>
                </div>
                <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-6">
                    <!-- COM Button -->
                    <a href="{{ route('superadmin.com') }}"
                       class="w-full md:w-1/2 text-center bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-12 rounded-xl shadow-lg transition-all duration-300 transform hover:-translate-y-1 text-xl">
                        COMM
                    </a>

                    <!-- CBR Button -->
                    <a href="{{ route('superadmin.cbr') }}"
                       class="w-full md:w-1/2 text-center bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-12 rounded-xl shadow-lg transition-all duration-300 transform hover:-translate-y-1 text-xl">
                        CBR
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Notification dropdown toggle
        const notificationBtn = document.getElementById('notification-btn');
        const notificationDropdown = document.getElementById('notification-dropdown');
        
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle('hidden');
            notificationDropdown.classList.toggle('opacity-0');
            notificationDropdown.classList.toggle('scale-95');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            if (!notificationDropdown.classList.contains('hidden')) {
                notificationDropdown.classList.add('hidden');
                notificationDropdown.classList.add('opacity-0');
                notificationDropdown.classList.add('scale-95');
            }
        });
        
        // Animate counting numbers
        const animateCounters = () => {
            const counters = document.querySelectorAll('.animate-count');
            const speed = 200;
            
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                
                if (target > 0) {
                    const increment = target / speed;
                    
                    if (count < target) {
                        counter.innerText = Math.ceil(count + increment);
                        setTimeout(updateCount, 1);
                    } else {
                        counter.innerText = target;
                    }
                    
                    function updateCount() {
                        const count = +counter.innerText;
                        
                        if (count < target) {
                            counter.innerText = Math.ceil(count + increment);
                            setTimeout(updateCount, 1);
                        } else {
                            counter.innerText = target;
                        }
                    }
                } else {
                    counter.innerText = target;
                }
            });
        };
        
        // Initialize counters
        animateCounters();
        
        // Check for new messages every 30 seconds
        setInterval(function() {
            fetch('{{ route("messages.unread-count") }}')
                .then(response => response.json())
                .then(data => {
                    const bellIcon = document.getElementById('notification-btn');
                    if (!bellIcon) return;
                    
                    const countBadge = bellIcon.querySelector('span');
                    
                    if (data.count > 0) {
                        if (!countBadge) {
                            const newBadge = document.createElement('span');
                            newBadge.className = 'absolute -top-1 -right-1 h-5 w-5 rounded-full bg-red-500 text-white text-xs flex items-center justify-center animate-bounce';
                            newBadge.textContent = data.count;
                            bellIcon.appendChild(newBadge);
                        } else {
                            countBadge.textContent = data.count;
                        }
                    } else if (countBadge) {
                        countBadge.remove();
                    }
                });
        }, 30000);
    });

    // Mark messages as seen when notification dropdown is opened
    document.getElementById('notification-btn')?.addEventListener('click', function() {
        fetch('{{ route("messages.mark-as-seen") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ _method: 'POST' })
        });
    });
</script>
@endsection