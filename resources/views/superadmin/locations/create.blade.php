@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Background blur -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

    <!-- Foreground content -->
    <div class="relative z-10 min-h-screen p-6 pb-16">
        <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-8 max-w-3xl mx-auto mt-10 animate-fadeIn">
            <h1 class="text-2xl md:text-3xl font-bold text-blue-900 mb-8 animate-slideInLeft">
                <i class="fas fa-plus-circle text-yellow-500 mr-2"></i>
                Nouvelle localité
            </h1>

            <form action="{{ route('superadmin.locations.store') }}" method="POST">
                @csrf

                <!-- Site Selection -->
                <div>
                    <label for="site_id" class="block text-blue-900 font-medium mb-2">Site</label>
                    <select name="site_id" id="site_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring focus:ring-blue-200 bg-white/90 backdrop-blur-sm">
                        <option value="">-- Sélectionner un site --</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>
                                {{ $site->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Name input -->
                <div class="mt-4">
                    <label for="name" class="block text-blue-900 font-medium mb-2">Nom de la localité</label>
                    <input type="text" name="name" id="name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring focus:ring-blue-200 bg-white/90 backdrop-blur-sm"
                        value="{{ old('name') }}"
                        placeholder="Entrez le nom de la localité">
                </div>

                <!-- Type Selection -->
                <div class="mt-4">
                    <label for="type" class="block text-blue-900 font-medium mb-2">Type de localité</label>
                    <select name="type" id="type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring focus:ring-blue-200 bg-white/90 backdrop-blur-sm">
                        <option value="">-- Choisir un type --</option>
                        @foreach(\App\Models\Location::getTypes() as $t)
                            <option value="{{ $t }}" {{ old('type', $location->type ?? '') === $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Floor Number Input (shown only for 'Étage' type) -->
                <div id="floorDiv" class="{{ old('type') === 'Étage' ? '' : 'hidden' }} mt-4">
                    <label for="floor_number" class="block text-blue-900 font-medium mb-2">
                        Numéro d'étage
                    </label>
                    <input type="number" name="floor_number" id="floor_number"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl"
                        value="{{ old('floor_number') }}"
                        min="0"
                        placeholder="Entrez le numéro d'étage">
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit"
                        class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <i class="fas fa-check mr-2"></i> Créer
                    </button>

                    <a href="{{ route('superadmin.locations.gestion-localite') }}"
                        class="ml-4 text-gray-600 hover:text-gray-800 transition duration-200">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('type').addEventListener('change', function() {
    const floorDiv = document.getElementById('floorDiv');
    floorDiv.classList.toggle('hidden', this.value !== 'Étage');
});
</script>
@endsection
