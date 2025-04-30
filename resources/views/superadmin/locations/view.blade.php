@extends('layouts.app')

@section('content')
<!-- CSRF Token Meta Tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>
    
    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-6 pb-16">
        <!-- Dashboard content with glassmorphism effect -->
        <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-8 max-w-7xl mx-auto mt-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-900">
                        Matériels dans {{ $entityType === 'room' ? 'la salle' : 'le couloir' }}: {{ $entity->name }}
                    </h1>
                    <p class="text-gray-600 mt-1">Localité: {{ $location->name }}</p>
                </div>
                
                <!-- Back button -->
                <div>
                    <a href="{{ $entityType === 'room' ? route('superadmin.locations.rooms', $location) : route('superadmin.locations.corridors', $location) }}" 
                       class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Materials Table -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg overflow-hidden">
                @if($materials->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-blue-50/50">
                            <tr>
                                <th class="px-6 py-3 text-blue-900 font-medium">Numéro d'inventaire</th>
                                <th class="px-6 py-3 text-blue-900 font-medium">Numéro de série</th>
                                <th class="px-6 py-3 text-blue-900 font-medium">Type</th>
                                <th class="px-6 py-3 text-blue-900 font-medium">État</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/50">
                            @foreach($materials as $material)
                            <tr class="hover:bg-blue-50/30 transition-colors duration-200">
                                <td class="px-6 py-4 font-medium">{{ $material->inventory_number }}</td>
                                <td class="px-6 py-4">{{ $material->serial_number }}</td>
                                <td class="px-6 py-4">
                                    @switch(get_class($material->materialable))
                                        @case('App\Models\Computer')
                                            Ordinateur
                                            @break
                                        @case('App\Models\Printer')
                                            Imprimante
                                            @break
                                        @case('App\Models\IpPhone')
                                            Téléphone IP
                                            @break
                                        @case('App\Models\Hotspot')
                                            Hotspot
                                            @break
                                        @default
                                            Inconnu
                                    @endswitch
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium 
                                        @if($material->state === 'bon') bg-green-100 text-green-800
                                        @elseif($material->state === 'défectueux') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $material->state }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-500">Aucun matériel trouvé</h3>
                    <p class="text-gray-400 mt-1">Cette {{ $entityType === 'room' ? 'salle' : 'couloir' }} ne contient aucun matériel</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection