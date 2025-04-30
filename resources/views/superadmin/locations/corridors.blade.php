@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>
    
    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-6 pb-16">
        <!-- Dashboard content with glassmorphism effect -->
        <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-8 max-w-7xl mx-auto mt-8 transition-all duration-500 transform hover:scale-[1.01]">
            <!-- Header with back button -->
            <div class="mb-8 flex justify-between items-center">
                <div class="animate-slideInLeft">
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-900">Gestion des couloirs</h1>
                    <p class="text-gray-600 mt-2">{{ $location->site->name }} - {{ $location->name ?? 'Localité' }}</p>
                </div>
                <a href="{{ route('superadmin.locations.gestion-localite') }}" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-1"></i> Retour
                </a>
            </div>

            <!-- Add corridor button -->
            <div class="mb-8 animate-slideInRight">
                <a href="{{ route('superadmin.locations.addcorridor', $location->id) }}" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 inline-flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i> Ajouter un couloir
                </a>
            </div>

            <!-- Corridors list -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl animate-fadeIn">
                @if($corridors->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-green-50/50">
                                <tr>
                                    <th class="px-6 py-3 text-green-900 font-medium">ID</th>
                                    <th class="px-6 py-3 text-green-900 font-medium">Nom</th>
                                    <th class="px-6 py-3 text-green-900 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200/50">
                                @foreach($corridors as $corridor)
                                    <tr class="hover:bg-green-50/30 transition-colors duration-200">
                                        <td class="px-6 py-4">#{{ $corridor->id }}</td>
                                        <td class="px-6 py-4">{{ $corridor->name ?? 'Non spécifié' }}</td>
                                        <td class="px-6 py-4 space-x-3">
                                            <form action="{{ route('superadmin.locations.destroyCorridor', [$location->id, $corridor->id]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" 
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce couloir?')">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-route text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Pas de couloirs dans cette localité.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection