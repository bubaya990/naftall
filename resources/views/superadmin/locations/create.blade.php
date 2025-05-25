@extends('layouts.app')

@section('content')
<!-- CSRF Token Meta Tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.webp'); filter: blur(6px);"></div>
    
    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16 flex items-center justify-center">
        <!-- Glass morphism container -->
        <div class="backdrop-blur-md bg-white/30 shadow-2xl rounded-2xl p-6 md:p-8 w-full max-w-3xl border-2 border-white/20 transition-all duration-500 transform hover:scale-[1.005] hover:shadow-xl">
            <!-- Header with floating animation -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 animate-float">
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800 drop-shadow-lg">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-800">
                        Nouvelle localité
                    </span>
                </h1>
                <a href="{{ route('superadmin.locations.gestion-localite') }}" 
                   class="btn btn-primary transform hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100/90 backdrop-blur-sm border-l-4 border-red-500 text-red-700 rounded-lg animate-shake">
                    <div class="font-bold">Veuillez corriger les erreurs suivantes :</div>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('superadmin.locations.store') }}" class="mt-4 space-y-6">
                @csrf

                <!-- Form container with glass effect -->
                <div class="overflow-x-auto w-full backdrop-filter backdrop-blur-sm bg-white/50 rounded-xl shadow-inner border-2 border-white/30">
                    <div class="grid-cols-1 md:grid-cols-2 gap-6 p-6">
                        <!-- Site Selection -->
                        <div class="col-span-1 animate-float" style="animation-delay: 0.1s">
                            <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="site_id">Site *</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                    <i class="fas fa-building"></i>
                                </div>
                                <select name="site_id" id="site_id"
                                    class="block w-full pl-10 pr-8 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 appearance-none shadow-sm group-hover:shadow-md"
                                    required>
                                    <option value="">-- Sélectionner un site --</option>
                                    @foreach($sites as $site)
                                        <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>
                                            {{ $site->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-blue-600/80">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Name input -->
                        <div class="col-span-1 animate-float" style="animation-delay: 0.2s">
                            <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="name">Nom de la localité *</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <input class="block w-full pl-10 pr-4 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 placeholder-gray-500/70 shadow-sm group-hover:shadow-md"
                                       id="name"
                                       type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required
                                       placeholder="Entrez le nom de la localité">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-xs text-blue-600 font-semibold">Requis</span>
                                </div>
                            </div>
                        </div>

                        <!-- Type Selection -->
                        <div class="col-span-1 animate-float" style="animation-delay: 0.3s">
                            <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="type">Type de localité *</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <select id="type" name="type"
                                        class="block w-full pl-10 pr-8 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 appearance-none shadow-sm group-hover:shadow-md"
                                        required
                                        onchange="toggleFloorField(this.value)">
                                    <option value="">-- Choisir un type --</option>
                                    @foreach(\App\Models\Location::getTypes() as $t)
                                        <option value="{{ $t }}" {{ old('type') === $t ? 'selected' : '' }}>
                                            {{ $t }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-blue-600/80">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Floor Number Input (shown only for 'Étage' type) -->
                        <div id="floorDiv" class="{{ old('type') === 'Étage' ? 'col-span-1' : 'hidden' }} animate-float" style="animation-delay: 0.4s">
                            <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="floor_number">Numéro d'étage *</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <input class="block w-full pl-10 pr-4 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 placeholder-gray-500/70 shadow-sm group-hover:shadow-md"
                                       id="floor_number"
                                       type="number"
                                       name="floor_number"
                                       value="{{ old('floor_number') }}"
                                       min="0"
                                       placeholder="Entrez le numéro d'étage">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-xs text-blue-600 font-semibold">Requis</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit button with floating effect -->
                <div class="flex justify-end mt-6 animate-float" style="animation-delay: 1.2s">
                    <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl flex items-center group">
                        <i class="fas fa-plus-circle mr-2 transition-transform group-hover:rotate-90"></i> 
                        <span class="relative">
                            Créer
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-white/50 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleFloorField(type) {
    const floorDiv = document.getElementById('floorDiv');
    const floorInput = document.getElementById('floor_number');
    
    if (type === 'Étage') {
        floorDiv.classList.remove('hidden');
        floorInput.setAttribute('required', 'required');
    } else {
        floorDiv.classList.add('hidden');
        floorInput.removeAttribute('required');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const initialType = document.getElementById('type').value;
    toggleFloorField(initialType);
    
    // Add staggered animation delays
    const formGroups = document.querySelectorAll('.animate-float');
    formGroups.forEach((group, index) => {
        group.style.animationDelay = `${index * 0.1}s`;
    });

    // Add hover effects to form inputs
    const inputs = document.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.classList.add('ring-2', 'ring-blue-400/30');
        });
        input.addEventListener('blur', () => {
            input.parentElement.classList.remove('ring-2', 'ring-blue-400/30');
        });
    });
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}



.animate-fadeIn {
    animation: fadeIn 0.6s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

.animate-shake {
    animation: shake 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
}

/* Glass morphism effects */
.backdrop-blur-md {
    backdrop-filter: blur(12px);
}

.bg-white\/30 {
    background-color: rgba(255, 255, 255, 0.3);
}

.bg-white\/50 {
    background-color: rgba(255, 255, 255, 0.5);
}

.bg-white\/70 {
    background-color: rgba(255, 255, 255, 0.7);
}

.bg-white\/90 {
    background-color: rgba(255, 255, 255, 0.9);
}

.border-white\/20 {
    border-color: rgba(255, 255, 255, 0.2);
}

.border-white\/30 {
    border-color: rgba(255, 255, 255, 0.3);
}

/* Gradient text */
.bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}

.from-blue-600 {
    --tw-gradient-from: #2563eb;
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(37, 99, 235, 0));
}

.to-indigo-700 {
    --tw-gradient-to: #4338ca;
}

.to-indigo-800 {
    --tw-gradient-to: #3730a3;
}

.hover\:from-blue-700:hover {
    --tw-gradient-from: #1d4ed8;
}

.hover\:to-indigo-800:hover {
    --tw-gradient-to: #3730a3;
}

/* Input field styling */
.border-blue-300\/50 {
    border-color: rgba(147, 197, 253, 0.5);
}

.focus\:ring-blue-500\/30:focus {
    --tw-ring-color: rgba(59, 130, 246, 0.3);
}

/* Button transitions */
.transition-all {
    transition-property: all;
}

.duration-300 {
    transition-duration: 300ms;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .backdrop-blur-md {
        backdrop-filter: blur(8px);
    }
    
    .grid-cols-1 {
        grid-template-columns: 1fr;
    }
}

/* Custom scrollbar for form container */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: rgba(59, 130, 246, 0.5);
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: rgba(59, 130, 246, 0.7);
}
</style>

@endsection