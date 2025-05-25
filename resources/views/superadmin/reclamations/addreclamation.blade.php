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
                        Nouvelle Réclamation
                    </span>
                </h1>
                <a href="{{ route('superadmin.reclamations') }}" 
                   class="btn btn-primary transform hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
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

            <form action="{{ route('storeReclamation') }}" method="POST" class="mt-4 space-y-6">
                @csrf
                
                <!-- Automatically generate claim number -->
                <input type="hidden" name="num_R" value="{{ 'REC-' . now()->format('Ymd-His') }}">

                <!-- Current date -->
                <input type="hidden" name="date_R" value="{{ now()->toDateString() }}">

                <!-- User ID (assuming authenticated user) -->
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                <!-- Form container with glass effect -->
                <div class="overflow-x-auto w-full backdrop-filter backdrop-blur-sm bg-white/50 rounded-xl shadow-inner border-2 border-white/30">
                    <div class="space-y-6 p-6">
                        <!-- Subject -->
                        <div class="animate-float" style="animation-delay: 0.1s">
                            <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="definition">Sujet de Réclamation *</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                    <i class="fas fa-heading"></i>
                                </div>
                                <input class="block w-full pl-10 pr-4 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 placeholder-gray-500/70 shadow-sm group-hover:shadow-md"
                                       id="definition"
                                       type="text"
                                       name="definition"
                                       value="{{ old('definition') }}"
                                       required
                                       placeholder="Entrez le sujet de votre réclamation">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-xs text-blue-600 font-semibold">Requis</span>
                                </div>
                            </div>
                            @error('definition')
                                <p class="text-red-500 text-sm mt-2 animate-pulse">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div class="animate-float" style="animation-delay: 0.2s">
                            <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="message">Message *</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 pt-3 flex items-start pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                    <i class="fas fa-comment-alt"></i>
                                </div>
                                <textarea id="message" name="message" rows="5"
                                          class="block w-full pl-10 pr-4 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 placeholder-gray-500/70 shadow-sm group-hover:shadow-md"
                                          placeholder="Décrivez votre réclamation en détail..."
                                          required>{{ old('message') }}</textarea>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-xs text-blue-600 font-semibold">Requis</span>
                                </div>
                            </div>
                            @error('message')
                                <p class="text-red-500 text-sm mt-2 animate-pulse">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit button with floating effect -->
                <div class="flex justify-between mt-6 animate-float" style="animation-delay: 0.3s">
                    <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-bold rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl flex items-center group">
                        <i class="fas fa-paper-plane mr-2 transition-transform group-hover:translate-x-1"></i> 
                        <span class="relative">
                            Envoyer la Réclamation
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-white/50 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></span>
                        </span>
                    </button>
                    
                    <a href="{{ route('superadmin.reclamations') }}"
                       class="px-6 py-3 text-gray-700 hover:text-gray-900 transition duration-200 rounded-xl border-2 border-gray-300 hover:border-gray-400 bg-white/70 hover:bg-white/90 flex items-center">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Keyframes for modern animations */
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

/* Animation classes */


.animate-fadeIn {
    animation: fadeIn 0.6s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

.animate-shake {
    animation: shake 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
}

.animate-pulse {
    animation: pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Glass morphism effects */
.backdrop-blur-md {
    backdrop-filter: blur(12px);
}

.bg-white\/30 {
    background-color: rgba(255, 255, 255, 0.3);
}

.border-white\/20 {
    border-color: rgba(255, 255, 255, 0.2);
}

/* Gradient text */
.bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}

.from-blue-600 {
    --tw-gradient-from: #2563eb;
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(37, 99, 235, 0));
}

.to-indigo-800 {
    --tw-gradient-to: #3730a3;
}

/* Button hover effects */
.hover\:from-yellow-600:hover {
    --tw-gradient-from: #d97706;
}

.hover\:to-yellow-700:hover {
    --tw-gradient-to: #b45309;
}

/* Input field transitions */
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add staggered animation delays
    const formGroups = document.querySelectorAll('.animate-float');
    formGroups.forEach((group, index) => {
        group.style.animationDelay = `${index * 0.1}s`;
    });

    // Add hover effects to form inputs
    const inputs = document.querySelectorAll('input, textarea, select');
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

@endsection