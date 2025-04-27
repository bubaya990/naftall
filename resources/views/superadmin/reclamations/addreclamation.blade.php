@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Background blur -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

    <!-- Foreground content -->
    <div class="relative z-10 min-h-screen p-6 pb-16">
        <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-8 max-w-3xl mx-auto mt-10 animate-fadeIn">
            <h1 class="text-2xl md:text-3xl font-bold text-blue-900 mb-8 animate-slideInLeft">
                <i class="fas fa-exclamation-circle text-yellow-500 mr-2"></i>
                Nouvelle Réclamation
            </h1>

            <form action="{{ route('storeReclamation') }}" method="POST">
                @csrf
                <!-- Automatically generate claim number -->
                <input type="hidden" name="num_R" value="{{ 'REC-' . now()->format('Ymd-His') }}">

                <!-- Current date -->
                <input type="hidden" name="date_R" value="{{ now()->toDateString() }}">

                <!-- User ID (assuming authenticated user) -->
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                <!-- Definition -->
                <div>
                    <label for="definition" class="block text-blue-900 font-medium mb-2">Sujet de Réclamation</label>
                    <select name="definition" id="definition" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring focus:ring-blue-200 bg-white/90 backdrop-blur-sm" required>
                        <option value="">-- Sélectionnez un sujet --</option>
                        <option value="Problème technique" {{ old('definition') == 'Problème technique' ? 'selected' : '' }}>Problème technique</option>
                        <option value="Réclamation administrative" {{ old('definition') == 'Réclamation administrative' ? 'selected' : '' }}>Réclamation administrative</option>
                        <option value="Problème de service" {{ old('definition') == 'Problème de service' ? 'selected' : '' }}>Problème de service</option>
                        <option value="Autre" {{ old('definition') == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('definition')
                        <p class="text-red-500 text-sm mt-2 animate-pulse">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Initial Message -->
                <div>
                    <label for="message" class="block text-blue-900 font-medium mb-2">Détails de la Réclamation</label>
                    <textarea id="message" name="message" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring focus:ring-blue-200 bg-white/90 backdrop-blur-sm"
                              placeholder="Décrivez votre réclamation en détail..." required>{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-red-500 text-sm mt-2 animate-pulse">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Receiver Selection (if applicable) -->
                @if(auth()->check() && auth()->user()->role === 'superadmin')

                <div>
                    <label for="receiver_id" class="block text-blue-900 font-medium mb-2">Destinataire</label>
                    <select name="receiver_id" id="receiver_id"
                           class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring focus:ring-blue-200 bg-white/90 backdrop-blur-sm">
                        <option value="">-- Sélectionnez un destinataire --</option>
                        @foreach($users as $user)
    <option value="{{ $user->id }}" {{ old('receiver_id') == $user->id ? 'selected' : '' }}>
        {{ $user->name }} ({{ $user->email }})
    </option>
@endforeach

                    </select>
                    @error('receiver_id')
                        <p class="text-red-500 text-sm mt-2 animate-pulse">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Form Actions -->
                <div class="pt-4 flex items-center justify-between">
                    <button type="submit"
                            class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <i class="fas fa-paper-plane mr-2"></i> Envoyer la Réclamation
                    </button>
                    
                    <a href="{{ route('superadmin.reclamations') }}"
                       class="ml-4 px-6 py-3 text-gray-600 hover:text-gray-800 transition duration-200 rounded-xl border border-gray-300 hover:border-gray-400">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection