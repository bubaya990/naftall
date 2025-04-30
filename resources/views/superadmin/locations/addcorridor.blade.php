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
                    <h1 class="text-2xl md:text-3xl font-bold text-green-900">Ajouter un couloir</h1>
                    <p class="text-gray-600 mt-2">{{ $location->site->name }} - {{ $location->name ?? 'Localité' }}</p>
                </div>
                <a href="{{ route('superadmin.locations.corridors', $location->id) }}" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-1"></i> Retour
                </a>
            </div>

            <!-- Add corridor form -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg p-6 animate-fadeIn">
                <form action="{{ route('superadmin.locations.storeCorridor', $location->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="name" class="block text-gray-700 font-medium mb-2">Nom du couloir (optionnel)</label>
                        <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 inline-flex items-center">
                            <i class="fas fa-save mr-2"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection