@extends('layouts.app')

@section('content')
<div class="relative">
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-2xl p-4 md:p-8 max-w-4xl w-full mx-auto mt-4 md:mt-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-xl md:text-2xl font-bold text-blue-900">Détails du Matériel</h1>
                <a href="{{ route('superadmin.materials.list', $type) }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>

            <form id="editForm" method="POST" action="{{ route('superadmin.materials.update', ['type' => $type, 'id' => $material->id]) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="hidden" name="id" value="{{ $material->id }}">
                
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <table class="w-full">
                        <tbody>
                            <tr class="border-b">
                                <td class="py-3 font-bold">N° Inventaire:</td>
                                <td>{{ $material->inventory_number }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 font-bold">N° Série:</td>
                                <td>{{ $material->serial_number }}</td>
                            </tr>
                            @if($type == 'computers')
                            <tr class="border-b">
                                <td class="py-3 font-bold">Marque:</td>
                                <td>{{ $material->materialable->computer_brand }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 font-bold">Modèle:</td>
                                <td>{{ $material->materialable->computer_model }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 font-bold">Système d'exploitation:</td>
                                <td>
                                    <select name="OS" class="p-1 border rounded">
                                        @foreach(['Windows7', 'Windows8', 'Windows10', 'Linux'] as $os)
                                            <option value="{{ $os }}" {{ $material->materialable->OS == $os ? 'selected' : '' }}>
                                                {{ $os }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            @endif
                            @if($type == 'hotspots')
                            <tr class="border-b">
                                <td class="py-3 font-bold">Mot de passe:</td>
                                <td>
                                    <input type="text" name="password" value="{{ $material->materialable->password }}" class="p-1 border rounded">
                                </td>
                            </tr>
                            @endif
                            <tr class="border-b">
                                <td class="py-3 font-bold">État:</td>
                                <td>
                                    <select name="state" class="p-1 border rounded {{ 
                                        $material->state == 'bon' ? 'bg-green-100 text-green-800' : 
                                        ($material->state == 'défectueux' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')
                                    }}">
                                        @foreach(['bon', 'défectueux', 'hors_service'] as $state)
                                            <option value="{{ $state }}" {{ $material->state == $state ? 'selected' : '' }}>
                                                {{ ucfirst($state) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            @if($type == 'computers')
                            <tr class="border-b">
                                <td class="py-3 font-bold">RAM:</td>
                                <td>
                                    @php
                                        $currentRam = $material->materialable->ram_capacity ?? $material->materialable->rams->capacity ?? null;
                                    @endphp
                                    <select name="ram_capacity" class="p-1 border rounded" required>
                                        @if($currentRam)
                                            <option value="{{ $currentRam }}" selected>{{ $currentRam }}</option>
                                        @else
                                            <option value="" selected>Sélectionner la RAM</option>
                                        @endif
                                        @foreach(['4GB', '8GB', '16GB', '32GB'] as $ramOption)
                                            @if($ramOption != $currentRam)
                                                <option value="{{ $ramOption }}">{{ $ramOption }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            @endif
                            <tr class="border-b">
                                <td class="py-3 font-bold">Emplacement:</td>
                                <td>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-gray-700 mb-1">Site</label>
                                            <select name="site_id" id="siteSelect" class="w-full p-2 border rounded" required>
                                                @foreach($sites as $site)
                                                    <option value="{{ $site->id }}" 
                                                        @if($material->room)
                                                            {{ $site->id == $material->room->location->site_id ? 'selected' : '' }}
                                                        @elseif($material->corridor)
                                                            {{ $site->id == $material->corridor->location->site_id ? 'selected' : '' }}
                                                        @endif>
                                                        {{ $site->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-gray-700 mb-1">Emplacement</label>
                                            <select name="location_id" id="locationSelect" class="w-full p-2 border rounded" required>
                                                @if($material->room)
                                                    @foreach($material->room->location->site->locations as $location)
                                                        <option value="{{ $location->id }}" {{ $location->id == $material->room->location_id ? 'selected' : '' }}>
                                                            {{ $location->type }}
                                                        </option>
                                                    @endforeach
                                                @elseif($material->corridor)
                                                    @foreach($material->corridor->location->site->locations as $location)
                                                        <option value="{{ $location->id }}" {{ $location->id == $material->corridor->location_id ? 'selected' : '' }}>
                                                            {{ $location->type }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-gray-700 mb-1">Type d'emplacement</label>
                                            <div class="flex space-x-4">
                                                <label class="flex items-center">
                                                    <input type="radio" name="location_type" value="room" {{ $material->room_id ? 'checked' : '' }} onchange="toggleLocationType()">
                                                    <span class="ml-2">Salle</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="radio" name="location_type" value="corridor" {{ $material->corridor_id ? 'checked' : '' }} onchange="toggleLocationType()">
                                                    <span class="ml-2">Couloir</span>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div id="roomField" class="{{ $material->corridor_id ? 'hidden' : '' }}">
                                            <label class="block text-gray-700 mb-1">Salle</label>
                                            <select name="room_id" id="roomSelect" class="w-full p-2 border rounded">
                                                @if($material->room)
                                                    @foreach($material->room->location->rooms as $room)
                                                        <option value="{{ $room->id }}" {{ $room->id == $material->room_id ? 'selected' : '' }}>
                                                            {{ $room->name }} ({{ $room->code }})
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        
                                        <div id="corridorField" class="{{ $material->room_id ? 'hidden' : '' }}">
                                            <label class="block text-gray-700 mb-1">Couloir</label>
                                            <select name="corridor_id" id="corridorSelect" class="w-full p-2 border rounded">
                                                @if($material->corridor)
                                                    @foreach($material->corridor->location->corridors as $corridor)
                                                        <option value="{{ $corridor->id }}" {{ $corridor->id == $material->corridor_id ? 'selected' : '' }}>
                                                            Couloir {{ $corridor->id }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleLocationType() {
    const roomRadio = document.querySelector('input[name="location_type"][value="room"]');
    document.getElementById('roomField').classList.toggle('hidden', !roomRadio.checked);
    document.getElementById('corridorField').classList.toggle('hidden', roomRadio.checked);
}

// Handle form submission
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Enregistrement...';
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            throw new Error(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        let errorMessage = 'Une erreur est survenue';
        if (error.errors) {
            errorMessage = Object.values(error.errors).join('<br>');
        } else if (error.message) {
            errorMessage = error.message;
        }
        
        // Show error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4';
        errorDiv.innerHTML = `<p>${errorMessage}</p>`;
        
        // Insert error message before the form
        form.parentNode.insertBefore(errorDiv, form);
        
        // Remove error after 5 seconds
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    })
    .finally(() => {
        submitButton.disabled = false;
        submitButton.innerHTML = '<i class="fas fa-save mr-2"></i>Enregistrer';
    });
});

// AJAX for dynamic location loading
document.getElementById('siteSelect')?.addEventListener('change', function() {
    const siteId = this.value;
    fetch(`/api/sites/${siteId}/locations`)
        .then(response => response.json())
        .then(locations => {
            const locationSelect = document.getElementById('locationSelect');
            locationSelect.innerHTML = '';
            locations.forEach(location => {
                const option = document.createElement('option');
                option.value = location.id;
                option.textContent = location.type;
                locationSelect.appendChild(option);
            });
            // Trigger change to load rooms/corridors for the first location
            if (locations.length > 0) {
                locationSelect.value = locations[0].id;
                locationSelect.dispatchEvent(new Event('change'));
            }
        });
});

document.getElementById('locationSelect')?.addEventListener('change', function() {
    const locationId = this.value;
    const locationType = document.querySelector('input[name="location_type"]:checked').value;
    
    if (locationType === 'room') {
        fetch(`/api/locations/${locationId}/rooms`)
            .then(response => response.json())
            .then(rooms => {
                const roomSelect = document.getElementById('roomSelect');
                roomSelect.innerHTML = '';
                rooms.forEach(room => {
                    const option = document.createElement('option');
                    option.value = room.id;
                    option.textContent = `${room.name} (${room.code})`;
                    roomSelect.appendChild(option);
                });
            });
    } else {
        fetch(`/api/locations/${locationId}/corridors`)
            .then(response => response.json())
            .then(corridors => {
                const corridorSelect = document.getElementById('corridorSelect');
                corridorSelect.innerHTML = '';
                corridors.forEach(corridor => {
                    const option = document.createElement('option');
                    option.value = corridor.id;
                    option.textContent = `Couloir ${corridor.id}`;
                    corridorSelect.appendChild(option);
                });
            });
    }
});
</script>
@endsection