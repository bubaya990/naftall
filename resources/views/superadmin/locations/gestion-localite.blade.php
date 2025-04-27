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
            <div class="mb-8">
                <div class="animate-slideInLeft">
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-900">Gestion des localités</h1>
                </div>
            </div>

            <!-- Create button with animation -->
            <div class="mb-8 animate-slideInRight">
                <a href="{{ route('superadmin.locations.create') }}" class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 inline-flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i>  Nouvelle localité
                </a>
            </div>

            <!-- Sites and Locations -->
            <div class="space-y-6">
                @foreach($sites as $site)
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl animate-fadeIn">
                    <div class="p-4 border-b border-gray-200/50 bg-gradient-to-r from-blue-50 to-blue-100">
                        <h2 class="font-semibold text-blue-900 text-xl flex items-center">
                            <i class="fas fa-building mr-2 text-yellow-600"></i>
                            {{ $site->name }}
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        @if($site->locations->count())
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead class="bg-blue-50/50">
                                        <tr>
                                            <th class="px-6 py-3 text-blue-900 font-medium">Type</th>
                                            <th class="px-6 py-3 text-blue-900 font-medium">Etage</th>
                                            <th class="px-6 py-3 text-blue-900 font-medium">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200/50">
                                        @foreach($site->locations as $location)
                                            <tr class="hover:bg-blue-50/30 transition-colors duration-200">
                                                <td class="px-6 py-4">{{ $location->type }}</td>
                                                <td class="px-6 py-4">{{ $location->floor->floor_number ?? '—' }}</td>
                                                <td class="px-6 py-4 space-x-3">
                                                    <a href="{{ route('superadmin.locations.rooms', $location->id) }}" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                                        <i class="fas fa-door-open mr-1"></i> Voir la salle
                                                    </a>

                                                    <a href="{{ route('superadmin.locations.edit-type', $location->id) }}" class="text-yellow-600 hover:text-yellow-800 transition-colors duration-200">
                                                        <i class="fas fa-edit mr-1"></i> Changer le type
                                                    </a>
                                                    <form action="{{ route('superadmin.locations.destroy', $location->id) }}" method="POST" class="inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="text-red-600 hover:text-red-900" 
            onclick="return confirm('Are you sure you want to delete this location?')">
        <i class="fas fa-trash"></i> Delete
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
                                <i class="fas fa-map-marker-alt text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Pas de localité dans ce site.</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection