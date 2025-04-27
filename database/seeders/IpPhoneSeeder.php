@extends('layouts.app')

@section('content')
<div class="relative">
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>
    <div class="relative z-10 min-h-screen p-6 pb-16">
        <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-8 max-w-7xl mx-auto mt-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-900">Liste des Téléphones IP</h1>
                    <p class="text-yellow-600 mt-2">{{ $ipphones->total() }} Téléphones IP trouvés</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('superadmin.materials.create', ['type' => 'ipphone']) }}" class="flex items-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i> Ajouter
                    </a>
                    <a href="{{ route('superadmin.materials.index') }}" class="flex items-center text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Inventaire</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Série</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adresse MAC</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Site</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Emplacement</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salle/Couloir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($ipphones as $material)
                            @php
                                $ipPhone = $material->materialable;
                                $location = $material->room ?: $material->corridor;
                                $site = $location ? $location->location->site : null;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $material->inventory_number ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $material->serial_number ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $ipPhone->mac_number ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $site->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $material->room->location->name ?? $material->corridor->location->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($material->room)
                                        Salle {{ $material->room->name }}
                                    @elseif($material->corridor)
                                        Couloir {{ $material->corridor->name }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $material->state === 'active' ? 'bg-green-100 text-green-800' : 
                                           ($material->state === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($material->state) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('superadmin.materials.show', ['type' => 'ipphone', 'material' => $material->id]) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('superadmin.materials.edit', ['type' => 'ipphone', 'material' => $material->id]) }}" 
                                           class="text-yellow-600 hover:text-yellow-900" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('superadmin.materials.destroy', ['type' => 'ipphone', 'material' => $material->id]) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce téléphone IP?')" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $ipphones->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection