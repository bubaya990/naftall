@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        <!-- Materials Form Section -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-2xl p-4 md:p-8 max-w-3xl w-full mx-auto mt-4 md:mt-8 transition-all duration-500 transform hover:scale-[1.005]">
            <!-- Header with actions -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <a href="{{ route('superadmin.materials.list', $type) }}" 
                   class="btn btn-primary transform hover:scale-105 transition-transform duration-300">
                   <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                </a>
                <h1 class="text-xl md:text-2xl font-bold text-blue-900">
                    Ajouter un 
                    @if($type == 'computers') Ordinateur
                    @elseif($type == 'printers') Imprimante
                    @elseif($type == 'ip-phones') Téléphone IP
                    @elseif($type == 'hotspots') Hotspot
                    @endif
                </h1>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg">
                    <div class="font-bold">Veuillez corriger les erreurs suivantes :</div>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('superadmin.materials.store', $type) }}" class="mt-4 space-y-6">
                @csrf

                <!-- Form container -->
                <div class="overflow-x-auto w-full backdrop-filter backdrop-blur-lg bg-blue-900/30 rounded-xl shadow-lg border border-blue-800/20">
                    <div class="grid grid-cols-1 gap-6 p-6">
                        <!-- Inventory Number -->
                        <div>
                            <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="inventory_number">N° Inventaire *</label>
                            <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                   id="inventory_number"
                                   type="text"
                                   name="inventory_number"
                                   value="{{ old('inventory_number') }}"
                                   required
                                   placeholder="Numéro d'inventaire">
                        </div>

                        <!-- Serial Number -->
                        <div>
                            <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="serial_number">N° Série *</label>
                            <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                   id="serial_number"
                                   type="text"
                                   name="serial_number"
                                   value="{{ old('serial_number') }}"
                                   required
                                   placeholder="Numéro de série">
                        </div>

                        <!-- State -->
                        <div>
                            <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="state">État *</label>
                            <select id="state" name="state"
                                    class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                    required>
                                @foreach($states as $state)
                                    <option value="{{ $state }}" {{ old('state') == $state ? 'selected' : '' }}>
                                        {{ ucfirst($state) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Type-specific fields -->
                        @if($type == 'computers')
                            <!-- Computer Brand -->
                            <div>
                                <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="computer_brand">Marque *</label>
                                <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                       id="computer_brand"
                                       type="text"
                                       name="computer_brand"
                                       value="{{ old('computer_brand') }}"
                                       required
                                       placeholder="Marque de l'ordinateur">
                            </div>

                            <!-- Computer Model -->
                            <div>
                                <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="computer_model">Modèle *</label>
                                <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                       id="computer_model"
                                       type="text"
                                       name="computer_model"
                                       value="{{ old('computer_model') }}"
                                       required
                                       placeholder="Modèle de l'ordinateur">
                            </div>

                            <!-- OS -->
                            <div>
                                <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="OS">Système d'exploitation *</label>
                                <select id="OS" name="OS"
                                        class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                        required>
                                    <option value="Windows7" {{ old('OS') == 'Windows7' ? 'selected' : '' }}>Windows 7</option>
                                    <option value="Windows8" {{ old('OS') == 'Windows8' ? 'selected' : '' }}>Windows 8</option>
                                    <option value="Windows10" {{ old('OS') == 'Windows10' ? 'selected' : '' }}>Windows 10</option>
                                    <option value="Linux" {{ old('OS') == 'Linux' ? 'selected' : '' }}>Linux</option>
                                </select>
                            </div>

                            <!-- RAM -->
                            <div>
                                <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="ram_id">RAM *</label>
                                <select id="ram_id" name="ram_id"
                                        class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                        required>
                                    @foreach(\App\Models\Ram::all() as $ram)
                                        <option value="{{ $ram->id }}" {{ old('ram_id') == $ram->id ? 'selected' : '' }}>
                                            {{ $ram->capacity }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        @elseif($type == 'printers')
                            <!-- Printer Brand -->
                            <div>
                                <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="printer_brand">Marque *</label>
                                <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                       id="printer_brand"
                                       type="text"
                                       name="printer_brand"
                                       value="{{ old('printer_brand') }}"
                                       required
                                       placeholder="Marque de l'imprimante">
                            </div>

                            <!-- Printer Model -->
                            <div>
                                <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="printer_model">Modèle *</label>
                                <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                       id="printer_model"
                                       type="text"
                                       name="printer_model"
                                       value="{{ old('printer_model') }}"
                                       required
                                       placeholder="Modèle de l'imprimante">
                            </div>

                        @elseif($type == 'ip-phones')
                            <!-- MAC Number -->
                            <div>
                                <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="mac_number">Numéro MAC *</label>
                                <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                       id="mac_number"
                                       type="text"
                                       name="mac_number"
                                       value="{{ old('mac_number') }}"
                                       required
                                       placeholder="Numéro MAC du téléphone">
                            </div>

                        @elseif($type == 'hotspots')
                            <!-- Password -->
                            <div>
                                <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="password">Mot de passe *</label>
                                <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                       id="password"
                                       type="text"
                                       name="password"
                                       value="{{ old('password') }}"
                                       required
                                       placeholder="Mot de passe du hotspot">
                            </div>
                        @endif

                        <!-- Site -->
                        <div>
                            <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="site_id">Site *</label>
                            <select id="site_id" name="site_id"
                                    class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                    required>
                                <option value="">Sélectionnez un site</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>
                                        {{ $site->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Location -->
                        <div>
                            <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="location_id">Emplacement *</label>
                            <select id="location_id" name="location_id"
                                    class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                    required>
                                <option value="">Sélectionnez d'abord un site</option>
                            </select>
                        </div>

                        <!-- Location Type -->
                        <div>
                            <label class="block text-sm md:text-base font-bold text-gray-900 mb-2">Type d'emplacement *</label>
                            <div class="flex space-x-6">
                                <label class="inline-flex items-center">
                                    <input type="radio" id="room_radio" name="location_type" value="room" checked 
                                           class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-blue-300">
                                    <span class="ml-2 text-gray-800">Salle</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" id="corridor_radio" name="location_type" value="corridor"
                                           class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-blue-300">
                                    <span class="ml-2 text-gray-800">Couloir</span>
                                </label>
                            </div>
                        </div>

                        <!-- Room Field -->
                        <div id="room_field">
                            <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="room_id">Salle</label>
                            <select id="room_id" name="room_id"
                                    class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800">
                                <option value="">Sélectionnez d'abord un emplacement</option>
                            </select>
                        </div>

                        <!-- Corridor Field -->
                        <div id="corridor_field" class="hidden">
                            <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="corridor_id">Couloir</label>
                            <select id="corridor_id" name="corridor_id"
                                    class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800">
                                <option value="">Sélectionnez d'abord un emplacement</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Submit button -->
                <div class="flex justify-end mt-6">
                    <button type="submit"
                            class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition duration-300 transform hover:scale-105">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic dropdowns for locations
    const siteSelect = document.getElementById('site_id');
    const locationSelect = document.getElementById('location_id');
    const roomSelect = document.getElementById('room_id');
    const corridorSelect = document.getElementById('corridor_id');
    const roomRadio = document.getElementById('room_radio');
    const corridorRadio = document.getElementById('corridor_radio');
    const roomField = document.getElementById('room_field');
    const corridorField = document.getElementById('corridor_field');
    
    // When site changes, load locations
    siteSelect.addEventListener('change', function() {
        const siteId = this.value;
        
        if (siteId) {
            fetch(`/superadmin/materials/get-locations/${siteId}`)
                .then(response => response.json())
                .then(data => {
                    locationSelect.innerHTML = '<option value="">Sélectionnez un emplacement</option>';
                    data.forEach(location => {
                        locationSelect.innerHTML += `<option value="${location.id}">${location.type}</option>`;
                    });
                });
        } else {
            locationSelect.innerHTML = '<option value="">Sélectionnez d\'abord un site</option>';
            roomSelect.innerHTML = '<option value="">Sélectionnez d\'abord un emplacement</option>';
            corridorSelect.innerHTML = '<option value="">Sélectionnez d\'abord un emplacement</option>';
        }
    });
    
    // When location changes, load rooms or corridors
    locationSelect.addEventListener('change', function() {
        const locationId = this.value;
        
        if (locationId) {
            // Load rooms
            fetch(`/superadmin/materials/get-rooms/${locationId}`)
                .then(response => response.json())
                .then(data => {
                    roomSelect.innerHTML = '<option value="">Sélectionnez une salle</option>';
                    data.forEach(room => {
                        roomSelect.innerHTML += `<option value="${room.id}">${room.name} (${room.code})</option>`;
                    });
                });
                
            // Load corridors
            fetch(`/superadmin/materials/get-corridors/${locationId}`)
                .then(response => response.json())
                .then(data => {
                    corridorSelect.innerHTML = '<option value="">Sélectionnez un couloir</option>';
                    data.forEach(corridor => {
                        corridorSelect.innerHTML += `<option value="${corridor.id}">Couloir ${corridor.id}</option>`;
                    });
                });
        } else {
            roomSelect.innerHTML = '<option value="">Sélectionnez d\'abord un emplacement</option>';
            corridorSelect.innerHTML = '<option value="">Sélectionnez d\'abord un emplacement</option>';
        }
    });
    
    // Toggle between room and corridor fields
    roomRadio.addEventListener('change', function() {
        if (this.checked) {
            roomField.classList.remove('hidden');
            corridorField.classList.add('hidden');
        }
    });
    
    corridorRadio.addEventListener('change', function() {
        if (this.checked) {
            roomField.classList.add('hidden');
            corridorField.classList.remove('hidden');
        }
    });
});
</script>
@endsection