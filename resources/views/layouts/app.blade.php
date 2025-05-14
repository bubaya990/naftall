<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ING Service - Naftal</title>
    <!-- CSRF Token should be here -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --naftal-blue: #001a33;
            --naftal-blue-light: #00264d;
            --naftal-orange: #FF6600;
        }

        .bg-naftal-blue { background-color: var(--naftal-blue); }
        .bg-naftal-blue-light { background-color: var(--naftal-blue-light); }
        .bg-naftal-orange { background-color: var(--naftal-orange); }
        .text-naftal-orange { color: var(--naftal-orange); }

        .sidebar-container {
            backdrop-filter: blur(12px);
            background-color: rgba(0, 26, 51, 0.85);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease-in-out;
            z-index: 40;
        }

        .sidebar-hidden {
            transform: translateX(-100%);
        }

        .sidebar-item {
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            margin: 0.25rem 0;
        }

        .sidebar-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
            border-left: 3px solid var(--naftal-orange);
            transform: scale(1.02);
        }

        .sidebar-item.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 3px solid var(--naftal-orange);
        }

        .profile-image {
            box-shadow: 0 0 0 2px var(--naftal-orange), 0 0 10px rgba(255, 102, 0, 0.5);
        }

        .status-dot {
            box-shadow: 0 0 0 2px var(--naftal-blue);
        }

        .toggle-btn {
            background-color: var(--naftal-blue);
            transition: all 0.3s ease;
            z-index: 50;
        }

        .toggle-btn:hover {
            background-color: var(--naftal-blue-light);
        }

        .toggle-btn-hidden {
            display: none;
        }
    </style>
</head>
@php $role = Auth::user()->role; @endphp
<meta name="csrf-token" content="{{ csrf_token() }}">

<body class="bg-gray-100 font-sans antialiased">
<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <div id="sidebar" class="w-64 fixed sidebar-container text-white flex flex-col flex-shrink-0 h-full sidebar-hidden">
        <div class="p-5 flex items-center border-b border-gray-700">
            <img src="/image/naftal-logo.jpg" class="h-10 w-auto mr-3 rounded-sm" alt="Naftal Logo">
            <div>
                <span class="font-bold text-lg tracking-tight">ING Service</span>
                <span class="text-xs text-gray-400 block">Naftal Petroleum</span>
            </div>
        </div>

        <!-- Profile -->
        @auth
        <div class="p-4 flex items-center border-b border-gray-700 bg-naftal-blue-light">
            <div class="relative">
                <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=FFFFFF&background=FF6600' }}"
                     class="w-10 h-10 rounded-full profile-image object-cover" alt="Profile">
                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 rounded-full status-dot"></span>
            </div>
            <div class="ml-3">
                <p class="font-medium text-sm">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400 uppercase tracking-wider">{{ Auth::user()->role }}</p>
            </div>
        </div>
        @endauth

        <!-- Role-Based Menu -->
        <div class="flex-1 overflow-y-auto py-3 px-3">
            @php $role = Auth::user()->role; @endphp

            {{-- SuperAdmin --}}
            @if($role === 'superadmin')
                <a href="{{ route('superadmin.dashboard') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                    <i class="fas fa-tachometer-alt w-5 text-center text-naftal-orange mr-3"></i>
                    <span>Tableau de bord</span>
                </a>
                <a href="{{ route('superadmin.utilisateurs') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                    <i class="fas fa-users w-5 text-center text-naftal-orange mr-3"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="{{ route('superadmin.utilisateurs.create') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                    <i class="fas fa-plus-circle w-5 text-center text-naftal-orange mr-3"></i>
                    <span>Créer un compte</span>
                </a>
                <a href="{{ route('superadmin.materials.index') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                    <i class="fas fa-boxes w-5 text-center text-naftal-orange mr-3"></i>
                    <span>Gestion Matériel</span>
                </a>
                <a href="{{ route('superadmin.locations.gestion-localite') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                    <i class="fas fa-map-marker-alt w-5 text-center text-naftal-orange mr-3"></i>
                    <span>Gestion Localité</span>
                </a>

            {{-- Admin --}}
            @elseif($role === 'admin')
                <a href="{{ route('superadmin.dashboard') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                    <i class="fas fa-tachometer-alt w-5 text-center text-naftal-orange mr-3"></i>
                    <span>Tableau de bord</span>
                </a>
                <a href="{{ route('superadmin.utilisateurs') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                    <i class="fas fa-users w-5 text-center text-naftal-orange mr-3"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="{{ route('superadmin.materials.index') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                    <i class="fas fa-boxes w-5 text-center text-naftal-orange mr-3"></i>
                    <span>Gestion Matériel</span>
                </a>
                <a href="{{ route('superadmin.locations.gestion-localite') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                    <i class="fas fa-map-marker-alt w-5 text-center text-naftal-orange mr-3"></i>
                    <span>Gestion Localité</span>
                </a>

            {{-- Leader --}}
            @elseif($role === 'leader')
                <a href="{{ route('superadmin.dashboard') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                    <i class="fas fa-tachometer-alt w-5 text-center text-naftal-orange mr-3"></i>
                    <span>Tableau de bord</span>
                </a>
                <a href="{{ route('superadmin.utilisateurs') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                    <i class="fas fa-users w-5 text-center text-naftal-orange mr-3"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="{{ route('superadmin.materials.index') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                    <i class="fas fa-boxes w-5 text-center text-naftal-orange mr-3"></i>
                    <span>Gestion Matériel</span>
                </a>
                <a href="{{ route('superadmin.locations.gestion-localite') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                    <i class="fas fa-map-marker-alt w-5 text-center text-naftal-orange mr-3"></i>
                    <span>Gestion Localité</span>
                </a>
            {{-- Utilisateur --}}
            @elseif($role === 'utilisateur')
                <a href="{{ route('superadmin.dashboard') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                    <i class="fas fa-tachometer-alt w-5 text-center text-naftal-orange mr-3"></i>
                    <span>Tableau de bord</span>
                </a>
                <a href="{{ route('superadmin.utilisateurs') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                    <i class="fas fa-users w-5 text-center text-naftal-orange mr-3"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="{{ route('superadmin.materials.index') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                    <i class="fas fa-boxes w-5 text-center text-naftal-orange mr-3"></i>
                    <span>Gestion Matériel</span>
                </a>
            @endif
        </div>

        <!-- Sidebar Bottom -->
        <div class="p-3 border-t border-gray-700 bg-naftal-blue-light">
            <a href="{{ route('statistiques') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                <i class="fas fa-chart-bar w-5 text-center text-naftal-orange mr-3"></i>
                <span>Statistiques</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium">
                <i class="fas fa-cog w-5 text-center text-naftal-orange mr-3"></i>
                <span>Paramètres</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-item flex items-center p-3 rounded-lg text-sm font-medium text-red-300 hover:text-red-200 w-full text-left">
                    <i class="fas fa-sign-out-alt w-5 text-center mr-3"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto bg-gray-50 transition-all duration-300" id="main-content">
        <button id="sidebar-toggle" class="fixed top-4 left-4 z-50 p-2 rounded-md text-white toggle-btn shadow-md">
            <i class="fas fa-bars"></i>
        </button>

        <div class="p-6 transition-all duration-300" id="page-container">
            @yield('content')
        </div>
    </div>
</div>
<script src="//unpkg.com/alpinejs" defer></script>

<script>
    const toggleBtn = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const pageContainer = document.getElementById('page-container');
    const mainContent = document.getElementById('main-content');

    // Hide sidebar by default and show toggle button
    sidebar.classList.add('sidebar-hidden');
    
    // Click handler for toggle button
    toggleBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('sidebar-hidden');
        toggleBtn.classList.toggle('toggle-btn-hidden');
    });

    // Click handler for main content to hide sidebar
    mainContent.addEventListener('click', () => {
        if (!sidebar.classList.contains('sidebar-hidden')) {
            sidebar.classList.add('sidebar-hidden');
            toggleBtn.classList.remove('toggle-btn-hidden');
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const currentPath = window.location.pathname;
        document.querySelectorAll('.sidebar-item').forEach(item => {
            const itemPath = item.getAttribute('href');
            if (currentPath.startsWith(itemPath)) {
                item.classList.add('active');
            }
        });
    });
</script>
</body>
</html>
