@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/naftalBg.jpeg'); filter: blur(6px);"></div>

    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        <!-- Materials Form Section -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-2xl p-4 md:p-8 max-w-3xl w-full mx-auto mt-4 md:mt-8 transition-all duration-500 transform hover:scale-[1.005]">
            <!-- Header with actions -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <a href="{{ $entityType === 'room' ?
                      route('superadmin.locations.rooms.materials', ['location' => $location->id, 'room' => $entity->id]) :
                      route('superadmin.locations.corridors.materials', ['location' => $location->id, 'corridor' => $entity->id]) }}"
                   class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300">
                   <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
                <h1 class="text-xl md:text-2xl font-bold text-blue-900">
                    {{ isset($material) ? 'Modifier' : 'Ajouter' }} un matériel à
                    {{ $entityType === 'room' ? 'la salle' : 'le couloir' }}: {{ $entity->name }}
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
            <form method="POST" action="{{ route('superadmin.locations.material.store', ['locationId' => $location->id, 'entityType' => $entityType, 'entityId' => $entity->id]) }}"id="materialForm" class="mt-4 space-y-6" id="materialForm" class="mt-4 space-y-6">
                @csrf
                @if(isset($material))
                    @method('PUT')
                @endif

                <!-- Hidden fields for location and entity -->
                <input type="hidden" name="location_type" value="{{ $entityType }}">
                @if($entityType === 'room')
                    <input type="hidden" name="room_id" value="{{ $entity->id }}">
                @else
                    <input type="hidden" name="corridor_id" value="{{ $entity->id }}">
                @endif
                <input type="hidden" name="location_id" value="{{ $location->id }}">

                <!-- Form container -->
                <div class="overflow-x-auto w-full backdrop-filter backdrop-blur-lg bg-blue-900/30 rounded-xl shadow-lg border border-blue-800/20">
                    <div class="grid grid-cols-1 gap-6 p-6">
                        <!-- Material Type Selection -->
                        <div>
                            <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="material_type">Type de matériel *</label>
                            <select id="material_type" name="material_type"
                                    class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                    required {{ isset($material) ? 'disabled' : '' }}>
                                <option value="">Sélectionnez un type</option>
                                <option value="computers" {{ $type == 'computers' ? 'selected' : '' }}>Ordinateur</option>
                                <option value="printers" {{ $type == 'printers' ? 'selected' : '' }}>Imprimante</option>
                                <option value="ip-phones" {{ $type == 'ip-phones' ? 'selected' : '' }}>Téléphone IP</option>
                                <option value="hotspots" {{ $type == 'hotspots' ? 'selected' : '' }}>Hotspot</option>
                            </select>
                        </div>

                        <!-- Inventory Number -->
                        <div>
                            <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="inventory_number">N° Inventaire *</label>
                            <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                   id="inventory_number"
                                   type="text"
                                   name="inventory_number"
                                   value="{{ old('inventory_number', $material->inventory_number ?? '') }}"
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
                                   value="{{ old('serial_number', $material->serial_number ?? '') }}"
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
                                    <option value="{{ $state }}" {{ old('state', $material->state ?? '') == $state ? 'selected' : '' }}>
                                        {{ ucfirst($state) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Computer Fields -->
                        <div id="computer_fields" style="{{ $type != 'computers' ? 'display: none;' : '' }}">
                            <!-- Computer Brand -->
                            <div>
                                <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="computer_brand">Marque *</label>
                                <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                       id="computer_brand"
                                       type="text"
                                       name="computer_brand"
                                       value="{{ old('computer_brand', $material->materialable->computer_brand ?? '') }}"
                                       {{ $type == 'computers' ? 'required' : '' }}
                                       placeholder="Marque de l'ordinateur">
                            </div>

                            <!-- Computer Model -->
                            <div>
                                <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="computer_model">Modèle *</label>
                                <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                       id="computer_model"
                                       type="text"
                                       name="computer_model"
                                       value="{{ old('computer_model', $material->materialable->computer_model ?? '') }}"
                                       {{ $type == 'computers' ? 'required' : '' }}
                                       placeholder="Modèle de l'ordinateur">
                            </div>

                            <!-- OS -->
                            <div>
                                <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="OS">Système d'exploitation *</label>
                                <select id="OS" name="OS"
                                        class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                        {{ $type == 'computers' ? 'required' : '' }}>
                                    <option value="">Sélectionnez un OS</option>
                                    <option value="Windows7" {{ old('OS', $material->materialable->OS ?? '') == 'Windows7' ? 'selected' : '' }}>Windows 7</option>
                                    <option value="Windows8" {{ old('OS', $material->materialable->OS ?? '') == 'Windows8' ? 'selected' : '' }}>Windows 8</option>
                                    <option value="Windows10" {{ old('OS', $material->materialable->OS ?? '') == 'Windows10' ? 'selected' : '' }}>Windows 10</option>
                                    <option value="Linux" {{ old('OS', $material->materialable->OS ?? '') == 'Linux' ? 'selected' : '' }}>Linux</option>
                                </select>
                            </div>

                            <!-- RAM -->
                            <div>
                                <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="ram_id">RAM *</label>
                                <select id="ram_id" name="ram_id"
                                        class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                        {{ $type == 'computers' ? 'required' : '' }}>
                                    <option value="">Sélectionnez une RAM</option>
                                    @foreach($rams as $ram)
                                        <option value="{{ $ram->id }}" {{ old('ram_id', $material->materialable->ram_id ?? '') == $ram->id ? 'selected' : '' }}>
                                            {{ $ram->capacity }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Printer Fields -->
                        <div id="printer_fields" style="{{ $type != 'printers' ? 'display: none;' : '' }}">
                            <!-- Printer Brand -->
                            <div>
                                <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="printer_brand">Marque *</label>
                                <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                       id="printer_brand"
                                       type="text"
                                       name="printer_brand"
                                       value="{{ old('printer_brand', $material->materialable->printer_brand ?? '') }}"
                                       {{ $type == 'printers' ? 'required' : '' }}
                                       placeholder="Marque de l'imprimante">
                            </div>

                            <!-- Printer Model -->
                            <div>
                                <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="printer_model">Modèle *</label>
                                <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                       id="printer_model"
                                       type="text"
                                       name="printer_model"
                                       value="{{ old('printer_model', $material->materialable->printer_model ?? '') }}"
                                       {{ $type == 'printers' ? 'required' : '' }}
                                       placeholder="Modèle de l'imprimante">
                            </div>
                        </div>

                        <!-- IP Phone Fields -->
                        <div id="ip_phone_fields" style="{{ $type != 'ip-phones' ? 'display: none;' : '' }}">
                            <!-- MAC Number -->
                            <div>
                                <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="mac_number">Numéro MAC *</label>
                                <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                       id="mac_number"
                                       type="text"
                                       name="mac_number"
                                       value="{{ old('mac_number', $material->materialable->mac_number ?? '') }}"
                                       {{ $type == 'ip-phones' ? 'required' : '' }}
                                       placeholder="Numéro MAC du téléphone">
                            </div>
                        </div>

                        <!-- Hotspot Fields -->
                        <div id="hotspot_fields" style="{{ $type != 'hotspots' ? 'display: none;' : '' }}">
                            <!-- Password -->
                            <div>
                                <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="password">Mot de passe *</label>
                                <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                       id="password"
                                       type="text"
                                       name="password"
                                       value="{{ old('password', $material->materialable->password ?? '') }}"
                                       {{ $type == 'hotspots' ? 'required' : '' }}
                                       placeholder="Mot de passe du hotspot">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit button -->
                <div class="flex justify-end mt-6">
                    <button type="submit"
                            class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition duration-300 transform hover:scale-105">
                        {{ isset($material) ? 'Mettre à jour' : 'Enregistrer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const materialTypeSelect = document.getElementById('material_type');

    // Function to show/hide fields based on selected type
    function updateFields() {
        const selectedType = materialTypeSelect.value;

        // Hide all field groups
        document.getElementById('computer_fields').style.display = 'none';
        document.getElementById('printer_fields').style.display = 'none';
        document.getElementById('ip_phone_fields').style.display = 'none';
        document.getElementById('hotspot_fields').style.display = 'none';

        // Show the selected field group
        if (selectedType === 'computers') {
            document.getElementById('computer_fields').style.display = 'block';
        } else if (selectedType === 'printers') {
            document.getElementById('printer_fields').style.display = 'block';
        } else if (selectedType === 'ip-phones') {
            document.getElementById('ip_phone_fields').style.display = 'block';
        } else if (selectedType === 'hotspots') {
            document.getElementById('hotspot_fields').style.display = 'block';
        }
    }

    // Initial field setup
    updateFields();

    // Update fields when type changes
    materialTypeSelect.addEventListener('change', function() {
        const type = this.value;
        if (type) {
            // Update URL without reloading the page
            const url = new URL(window.location.href);
            const pathParts = url.pathname.split('/');

            // Check if URL already has a type
            const lastPart = pathParts[pathParts.length - 1];
            const hasType = ['computers', 'printers', 'ip-phones', 'hotspots'].includes(lastPart);

            if (hasType) {
                // Replace the existing type
                pathParts[pathParts.length - 1] = type;
            } else {
                // Add the type to URL
                pathParts.push(type);
            }

            // Update URL without reloading
            window.history.pushState({}, '', pathParts.join('/'));

            // Update the visible fields
            updateFields();
        }
    });
});
</script>
@endsection