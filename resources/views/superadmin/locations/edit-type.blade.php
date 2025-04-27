@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Background blur -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

    <!-- Foreground content -->
    <div class="relative z-10 min-h-screen p-6 pb-16">
        <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-8 max-w-3xl mx-auto mt-10 animate-fadeIn">
            <h1 class="text-2xl md:text-3xl font-bold text-blue-900 mb-8 animate-slideInLeft">
                <i class="fas fa-edit text-yellow-500 mr-2"></i>
                Edit Location Type
            </h1>

            <form action="{{ route('locations.update-type', $location->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Type dropdown -->
                <div>
                    <label for="type" class="block text-blue-900 font-medium mb-2">Select Location Type</label>
                    <select name="type" id="type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring focus:ring-blue-200 bg-white/90 backdrop-blur-sm">
                        <option value="">-- Choose Type --</option>
                        <option value="Post Police" {{ old('type', $location->type) == 'Post Police' ? 'selected' : '' }}>Post Police</option>
                        <option value="Rez-de-chaussée" {{ old('type', $location->type) == 'Rez-de-chaussée' ? 'selected' : '' }}>Rez-de-chaussée</option>
                        <option value="Étage" {{ old('type', $location->type) == 'Étage' ? 'selected' : '' }}>Étage</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit"
                        class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <i class="fas fa-check mr-2"></i> Update
                    </button>

                    <a href="{{ route('locations.gestionLocalite') }}"
                        class="ml-4 text-gray-600 hover:text-gray-800 transition duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
