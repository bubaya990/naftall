@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="relative min-h-screen">
    <!-- Enhanced Background with Layered Blur -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.webp'); filter: blur(12px) brightness(0.9); transform: scale(1.05);"></div>
    <div class="fixed inset-0 bg-gradient-to-br from-green-900/20 to-green-800/30 z-0"></div>

    <!-- Main Container -->
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16 flex items-center justify-center">
        <!-- Glass Card Container -->
        <div class="w-full max-w-4xl">
            <!-- Floating Card with Glass Effect -->
            <div class="backdrop-blur-xl bg-white/95 bg-green-50/5 rounded-2xl shadow-2xl overflow-hidden border border-white/20 transform transition-all duration-500 hover:shadow-3xl hover:-translate-y-1">
                <!-- Card Header with Gradient -->
                <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 text-white">
                    <div class="flex justify-between items-center">
                        <h1 class="text-2xl md:text-3xl font-bold flex items-center">
                            <i class="fas fa-laptop mr-3"></i>
                            Détails du Matériel
                        </h1>
 <a href="{{ $source === 'view' ? route('superadmin.locations.view', ['location' => $material->room->location->id ?? $material->corridor->location->id, 'entityType' => $material->room_id ? 'room' : 'corridor', 'entity' => $material->room_id ?? $material->corridor_id]) : route('superadmin.materials.list', $type) }}" 
   class="btn btn-outline-light flex items-center px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 border-white/30 transition-all">
    <i class="fas fa-arrow-left mr-2"></i> Retour
</a>
                    </div>
                    <div class="mt-2 flex items-center text-green-100">
                        <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-medium">
                            {{ strtoupper($type) }}
                        </span>
                        <span class="mx-2">•</span>
                        <span class="font-mono">{{ $material->inventory_number }}</span>
                    </div>
                </div>

                <!-- Card Body with slight green tint -->
                <div class="bg-white/95 bg-green-50/5 backdrop-blur-sm p-6 md:p-8">
                    <form id="editForm" method="POST" action="{{ route('superadmin.materials.update', ['type' => $type, 'id' => $material->id]) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type" value="{{ $type }}">
                        <input type="hidden" name="id" value="{{ $material->id }}">

                        <!-- Material Details Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <!-- Inventory Number -->
                                <div class="bg-white rounded-xl p-5 shadow-sm border border-green-100">
                                    <div class="flex items-center">
                                        <i class="fas fa-barcode text-green-600 mr-3"></i>
                                        <div>
                                            <label class="block text-sm font-medium text-green-700 mb-1">N° Inventaire</label>
                                            <div class="text-lg font-mono font-bold text-green-900">{{ $material->inventory_number }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Serial Number -->
                                <div class="bg-white rounded-xl p-5 shadow-sm border border-green-100">
                                    <div class="flex items-center">
                                        <i class="fas fa-hashtag text-green-600 mr-3"></i>
                                        <div>
                                            <label class="block text-sm font-medium text-green-700 mb-1">N° Série</label>
                                            <div class="text-lg font-mono font-bold text-green-900">{{ $material->serial_number }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- State Selector -->
                                <div class="bg-white rounded-xl p-5 shadow-sm border border-green-100">
                                    <div class="flex items-center">
                                        <i class="fas fa-battery-three-quarters text-green-600 mr-3"></i>
                                        <div class="flex-1">
                                            <label class="block text-sm font-medium text-green-700 mb-2">État</label>
                                            <div class="relative">
                                                <select name="state" class="block w-full pl-3 pr-10 py-2 text-base border border-green-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 rounded-lg transition-all
                                                    {{ $material->state == 'bon' ? 'bg-green-50 text-green-800' : 
                                                       ($material->state == 'défectueux' ? 'bg-yellow-50 text-yellow-800' : 'bg-red-50 text-red-800') }}">
                                                    @foreach(['bon', 'défectueux', 'hors_service'] as $state)
                                                        <option value="{{ $state }}" {{ $material->state == $state ? 'selected' : '' }}>
                                                            {{ ucfirst($state) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                    <i class="fas fa-chevron-down text-gray-600"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column - Type Specific Fields -->
                            <div class="space-y-6">
                                @if($type == 'computers')
                                    <!-- Computer Brand -->
                                    <div class="bg-white rounded-xl p-5 shadow-sm border border-green-100">
                                        <div class="flex items-center">
                                            <i class="fas fa-tag text-green-600 mr-3"></i>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 mb-1">Marque</label>
                                                <div class="text-lg font-semibold text-gray-800">{{ $material->materialable->computer_brand }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Computer Model -->
                                    <div class="bg-white rounded-xl p-5 shadow-sm border border-green-100">
                                        <div class="flex items-center">
                                            <i class="fas fa-laptop-code text-green-600 mr-3"></i>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 mb-1">Modèle</label>
                                                <div class="text-lg font-semibold text-gray-800">{{ $material->materialable->computer_model }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- OS Selector -->
                                    <div class="bg-white rounded-xl p-5 shadow-sm border border-green-100">
                                        <div class="flex items-center">
                                            <i class="fab fa-windows text-blue-500 mr-3"></i>
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-green-700 mb-2">Système d'exploitation</label>
                                                <div class="relative">
                                                    <select name="OS" class="block w-full pl-3 pr-10 py-2 text-base border border-green-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 rounded-lg bg-white transition-all">
                                                        @foreach(['Windows7', 'Windows8', 'Windows10', 'Linux'] as $os)
                                                            <option value="{{ $os }}" {{ $material->materialable->OS == $os ? 'selected' : '' }}>
                                                                {{ $os }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                        <i class="fas fa-chevron-down text-gray-600"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- RAM Selector -->
                                    <div class="bg-white rounded-xl p-5 shadow-sm border border-green-100">
                                        <div class="flex items-center">
                                            <i class="fas fa-memory text-purple-500 mr-3"></i>
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-green-700 mb-2">RAM</label>
                                                @php
                                                    $currentRam = $material->materialable->ram_capacity ?? $material->materialable->rams->capacity ?? null;
                                                @endphp
                                                <div class="relative">
                                                    <select name="ram_capacity" class="block w-full pl-3 pr-10 py-2 text-base border border-green-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 rounded-lg bg-white transition-all" required>
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
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                        <i class="fas fa-chevron-down text-gray-600"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($type == 'hotspots')
                                    <!-- Hotspot Password -->
                                    <div class="bg-white rounded-xl p-5 shadow-sm border border-green-100">
                                        <div class="flex items-center">
                                            <i class="fas fa-key text-yellow-500 mr-3"></i>
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-green-700 mb-2">Mot de passe</label>
                                                <div class="relative">
                                                    <input type="text" name="password" value="{{ $material->materialable->password }}" 
                                                           class="block w-full pl-3 pr-10 py-2 text-base border border-green-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 rounded-lg bg-white transition-all">
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                        <i class="fas fa-eye text-gray-600 cursor-pointer hover:text-gray-700"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-green-100 mt-6">
                            <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-green-600"></i>
                                Emplacement
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Site Selector -->
                                <div>
                                    <div class="flex items-center">
                                        <i class="fas fa-building text-green-600 mr-3"></i>
                                        <div class="flex-1">
                                            <label class="block text-sm font-medium text-green-700 mb-2">Site</label>
                                            <div class="relative">
                                                <select name="site_id" id="siteSelect" class="block w-full pl-3 pr-10 py-2 text-base border border-green-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 rounded-lg bg-white transition-all" required>
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
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                    <i class="fas fa-chevron-down text-gray-600"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Selector -->
                                <div>
                                    <div class="flex items-center">
                                        <i class="fas fa-location-arrow text-green-600 mr-3"></i>
                                        <div class="flex-1">
                                            <label class="block text-sm font-medium text-green-700 mb-2">Emplacement</label>
                                            <div class="relative">
                                                <select name="location_id" id="locationSelect" class="block w-full pl-3 pr-10 py-2 text-base border border-green-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 rounded-lg bg-white transition-all" required>
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
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                    <i class="fas fa-chevron-down text-gray-600"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Type Radio -->
                                <div class="md:col-span-2">
                                    <div class="flex items-center">
                                        <i class="fas fa-layer-group text-green-600 mr-3"></i>
                                        <div class="flex-1">
                                            <label class="block text-sm font-medium text-green-700 mb-2">Type d'emplacement</label>
                                            <div class="flex space-x-6 bg-green-50 rounded-lg p-3 border border-green-200">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="location_type" value="room" {{ $material->room_id ? 'checked' : '' }} onchange="toggleLocationType()" 
                                                           class="h-5 w-5 text-green-600 focus:ring-green-500 border-green-300">
                                                    <span class="ml-2 text-gray-800 font-medium">Salle</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="location_type" value="corridor" {{ $material->corridor_id ? 'checked' : '' }} onchange="toggleLocationType()" 
                                                           class="h-5 w-5 text-green-600 focus:ring-green-500 border-green-300">
                                                    <span class="ml-2 text-gray-800 font-medium">Couloir</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Room Selector -->
                                <div id="roomField" class="{{ $material->corridor_id ? 'hidden' : '' }}">
                                    <div class="flex items-center">
                                        <i class="fas fa-door-open text-green-600 mr-3"></i>
                                        <div class="flex-1">
                                            <label class="block text-sm font-medium text-green-700 mb-2">Salle</label>
                                            <div class="relative">
                                                <select name="room_id" id="roomSelect" class="block w-full pl-3 pr-10 py-2 text-base border border-green-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 rounded-lg bg-white transition-all">
                                                    @if($material->room)
                                                        @foreach($material->room->location->rooms as $room)
                                                            <option value="{{ $room->id }}" {{ $room->id == $material->room_id ? 'selected' : '' }}>
                                                                {{ $room->name }} ({{ $room->code }})
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                    <i class="fas fa-chevron-down text-gray-600"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Corridor Selector -->
                                <div id="corridorField" class="{{ $material->room_id ? 'hidden' : '' }}">
                                    <div class="flex items-center">
                                        <i class="fas fa-arrows-alt-h text-green-600 mr-3"></i>
                                        <div class="flex-1">
                                            <label class="block text-sm font-medium text-green-700 mb-2">Couloir</label>
                                            <div class="relative">
                                                <select name="corridor_id" id="corridorSelect" class="block w-full pl-3 pr-10 py-2 text-base border border-green-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 rounded-lg bg-white transition-all">
                                                    @if($material->corridor)
                                                        @foreach($material->corridor->location->corridors as $corridor)
                                                            <option value="{{ $corridor->id }}" {{ $corridor->id == $material->corridor_id ? 'selected' : '' }}>
                                                                Couloir {{ $corridor->id }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                    <i class="fas fa-chevron-down text-gray-600"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end mt-8">
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl flex items-center group">
                                <i class="fas fa-save mr-2 transition-transform group-hover:rotate-12"></i>
                                <span class="relative">
                                    Enregistrer les modifications
                                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-white/50 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleLocationType() {
    const roomRadio = document.querySelector('input[name="location_type"][value="room"]');
    document.getElementById('roomField').classList.toggle('hidden', !roomRadio.checked);
    document.getElementById('corridorField').classList.toggle('hidden', roomRadio.checked);
    
    // Trigger change to load appropriate rooms/corridors
    document.getElementById('locationSelect').dispatchEvent(new Event('change'));
}

// Handle form submission with modern fetch API
document.getElementById('editForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Show loading state
    submitButton.disabled = true;
    submitButton.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Enregistrement...
    `;
    
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw errorData;
        }

        const data = await response.json();
        
        if (data.success) {
            // Show success notification
            showNotification('Modifications enregistrées avec succès!', 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            throw new Error(data.message || 'Une erreur est survenue');
        }
    } catch (error) {
        let errorMessage = 'Une erreur est survenue';
        if (error.errors) {
            errorMessage = Object.values(error.errors).join('<br>');
        } else if (error.message) {
            errorMessage = error.message;
        }
        
        showNotification(errorMessage, 'error');
    } finally {
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    }
});

// AJAX for dynamic location loading
document.getElementById('siteSelect')?.addEventListener('change', async function() {
    const siteId = this.value;
    try {
        const response = await fetch(`/api/sites/${siteId}/locations`);
        const locations = await response.json();
        
        const locationSelect = document.getElementById('locationSelect');
        locationSelect.innerHTML = '';
        locations.forEach(location => {
            const option = document.createElement('option');
            option.value = location.id;
            option.textContent = location.type;
            locationSelect.appendChild(option);
        });
        
        if (locations.length > 0) {
            locationSelect.value = locations[0].id;
            locationSelect.dispatchEvent(new Event('change'));
        }
    } catch (error) {
        console.error('Error loading locations:', error);
    }
});

document.getElementById('locationSelect')?.addEventListener('change', async function() {
    const locationId = this.value;
    const locationType = document.querySelector('input[name="location_type"]:checked').value;
    
    try {
        if (locationType === 'room') {
            const response = await fetch(`/api/locations/${locationId}/rooms`);
            const rooms = await response.json();
            
            const roomSelect = document.getElementById('roomSelect');
            roomSelect.innerHTML = '';
            rooms.forEach(room => {
                const option = document.createElement('option');
                option.value = room.id;
                option.textContent = `${room.name} (${room.code})`;
                roomSelect.appendChild(option);
            });
        } else {
            const response = await fetch(`/api/locations/${locationId}/corridors`);
            const corridors = await response.json();
            
            const corridorSelect = document.getElementById('corridorSelect');
            corridorSelect.innerHTML = '';
            corridors.forEach(corridor => {
                const option = document.createElement('option');
                option.value = corridor.id;
                option.textContent = `Couloir ${corridor.id}`;
                corridorSelect.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading rooms/corridors:', error);
    }
});

// Notification function
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white font-medium flex items-center ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'} mr-2"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('opacity-0', 'transition-opacity', 'duration-300');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}
</script>

<style>
/* Enhanced glass morphism effects */
.backdrop-blur-xl {
    backdrop-filter: blur(16px);
}

/* Gradient backgrounds */
.bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}

/* Color definitions */
.from-green-600 {
    --tw-gradient-from: #16a34a;
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(22, 163, 74, 0));
}
.to-green-700 {
    --tw-gradient-to: #15803d;
}

/* Button hover effects */
.hover\:from-green-600:hover {
    --tw-gradient-from: #16a34a;
}
.hover\:to-green-700:hover {
    --tw-gradient-to: #15803d;
}

/* Form input transitions */
.transition-all {
    transition-property: all;
}
.duration-300 {
    transition-duration: 300ms;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}
::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
}
::-webkit-scrollbar-thumb {
    background: rgba(22, 163, 74, 0.5);
    border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
    background: rgba(22, 163, 74, 0.7);
}

/* Animation for form elements */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fadeIn {
    animation: fadeIn 0.5s ease-out forwards;
}

/* State-specific colors */
.bg-green-50 {
    background-color: #f0fdf4;
}
.bg-yellow-50 {
    background-color: #fefce8;
}
.bg-red-50 {
    background-color: #fef2f2;
}
.text-green-800 {
    color: #166534;
}
.text-yellow-800 {
    color: #854d0e;
}
.text-red-800 {
    color: #991b1b;
}
.border-green-200 {
    border-color: #bbf7d0;
}

/* Added slight green tint to white backgrounds */
.bg-green-50\/5 {
    background-color: rgba(240, 253, 244, 0.03);
}
</style>
@endsection