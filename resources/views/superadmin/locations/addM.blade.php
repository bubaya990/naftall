@extends('layouts.app')

@section('content')
<div class="relative">
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/naftalBg.jpeg'); filter: blur(6px);"></div>

    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-2xl p-4 md:p-8 max-w-3xl w-full mx-auto mt-4 md:mt-8 transition-all duration-500 transform hover:scale-[1.005]">
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

            <form action="{{ route('superadmin.locations.materials.store', [
    'locationId' => $location->id,
    'entityType' => $entityType,
    'entityId' => $entity->id
]) }}" method="POST">
                @csrf

                <div>
                    <label for="inventory_number" class="block text-gray-700 text-sm font-bold mb-2">Numéro d'inventaire</label>
                    <input type="text" id="inventory_number" name="inventory_number" value="{{ old('inventory_number', $material->inventory_number ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <input type="hidden" name="name" value="Matériel"> {{-- Hidden name field with a default value --}}
                <div>
                    <label for="serial_number" class="block text-gray-700 text-sm font-bold mb-2">Numéro de série</label>
                    <input type="text" id="serial_number" name="serial_number" value="{{ old('serial_number', $material->serial_number ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label for="state" class="block text-gray-700 text-sm font-bold mb-2">État</label>
                    <select id="state" name="state" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Sélectionner l'état</option>
                        @foreach ($states as $stateOption)
                            <option value="{{ $stateOption }}" {{ old('state', $material->state ?? '') == $stateOption ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $stateOption)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Type de matériel</label>
                    <select id="type" name="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" onchange="toggleSpecificFields(this.value)">
                        <option value="computers" {{ old('type', $type ?? '') == 'computers' ? 'selected' : '' }}>Ordinateur</option>
                        <option value="printers" {{ old('type', $type ?? '') == 'printers' ? 'selected' : '' }}>Imprimante</option>
                        <option value="ip-phones" {{ old('type', $type ?? '') == 'ip-phones' ? 'selected' : '' }}>Téléphone IP</option>
                        <option value="hotspots" {{ old('type', $type ?? '') == 'hotspots' ? 'selected' : '' }}>Borne Wi-Fi</option>
                    </select>
                </div>

                <div id="computer_fields" style="{{ old('type', $type ?? '') == 'computers' ? '' : 'display: none;' }}">
                    <div>
                        <label for="computer_brand" class="block text-gray-700 text-sm font-bold mb-2">Marque de l'ordinateur</label>
                        <input type="text" id="computer_brand" name="computer_brand" value="{{ old('computer_brand', $material->materialable->computer_brand ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label for="computer_model" class="block text-gray-700 text-sm font-bold mb-2">Modèle de l'ordinateur</label>
                        <input type="text" id="computer_model" name="computer_model" value="{{ old('computer_model', $material->materialable->computer_model ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label for="OS" class="block text-gray-700 text-sm font-bold mb-2">Système d'exploitation</label>
                        <select id="OS" name="OS" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Sélectionner l'OS</option>
                            <option value="Windows7" {{ old('OS', $material->materialable->OS ?? '') == 'Windows7' ? 'selected' : '' }}>Windows 7</option>
                            <option value="Windows8" {{ old('OS', $material->materialable->OS ?? '') == 'Windows8' ? 'selected' : '' }}>Windows 8</option>
                            <option value="Windows10" {{ old('OS', $material->materialable->OS ?? '') == 'Windows10' ? 'selected' : '' }}>Windows 10</option>
                            <option value="Linux" {{ old('OS', $material->materialable->OS ?? '') == 'Linux' ? 'selected' : '' }}>Linux</option>
                        </select>
                    </div>
                    <div>
                        <label for="ram_id" class="block text-gray-700 text-sm font-bold mb-2">RAM</label>
                        <select id="ram_id" name="ram_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Sélectionner la RAM</option>
                            @foreach ($rams as $ram)
                                <option value="{{ $ram->id }}" {{ old('ram_id', $material->materialable->ram_id ?? '') == $ram->id ? 'selected' : '' }}>{{ $ram->capacity }} Go</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="printer_fields" style="{{ old('type', $type ?? '') == 'printers' ? '' : 'display: none;' }}">
                    <div>
                        <label for="printer_brand" class="block text-gray-700 text-sm font-bold mb-2">Marque de l'imprimante</label>
                        <input type="text" id="printer_brand" name="printer_brand" value="{{ old('printer_brand', $material->materialable->printer_brand ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label for="printer_model" class="block text-gray-700 text-sm font-bold mb-2">Modèle de l'imprimante</label>
                        <input type="text" id="printer_model" name="printer_model" value="{{ old('printer_model', $material->materialable->printer_model ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <div id="ip-phones_fields" style="{{ old('type', $type ?? '') == 'ip-phones' ? '' : 'display: none;' }}">
                    <div>
                        <label for="mac_number" class="block text-gray-700 text-sm font-bold mb-2">Adresse MAC</label>
                        <input type="text" id="mac_number" name="mac_number" value="{{ old('mac_number', $material->materialable->mac_number ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <div id="hotspots_fields" style="{{ old('type', $type ?? '') == 'hotspots' ? '' : 'display: none;' }}">
                    <div>
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Mot de passe</label>
                        <input type="text" id="password" name="password" value="{{ old('password', $material->materialable->password ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-300 focus:outline-none focus:shadow-outline">
                        {{ isset($material) ? 'Modifier' : 'Enregistrer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleSpecificFields(type) {
        document.getElementById('computer_fields').style.display = type === 'computers' ? 'block' : 'none';
        document.getElementById('printer_fields').style.display = type === 'printers' ? 'block' : 'none';
        document.getElementById('ip-phones_fields').style.display = type === 'ip-phones' ? 'block' : 'none';
        document.getElementById('hotspots_fields').style.display = type === 'hotspots' ? 'block' : 'none';
    }

    // Initialize visibility on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleSpecificFields(document.getElementById('type').value);
    });
</script>
@endsection