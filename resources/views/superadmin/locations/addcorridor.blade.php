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
        <div class="backdrop-blur-xl bg-white/20 shadow-2xl rounded-2xl p-6 md:p-8 w-full max-w-3xl border-2 border-white/20">
            <!-- Header with gradient text -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800 drop-shadow-lg">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-600 to-green-800">
                        Nouveau Couloir
                    </span>
                </h1>
                <a href="{{ route('superadmin.locations.corridors', $location->id) }}" 
                   class="btn btn-primary transform hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-full shadow-lg hover:shadow-xl flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100/90 backdrop-blur-sm border-l-4 border-red-500 text-red-700 rounded-lg">
                    <div class="font-bold">Veuillez corriger les erreurs suivantes :</div>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form container -->
            <div class="backdrop-blur-lg bg-white/40 rounded-xl shadow-inner border-2 border-white/30 p-6">
                <form action="{{ route('superadmin.locations.storeCorridor', $location->id) }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Corridor Name Field -->
                        <div>
                            <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="name">Nom du couloir (optionnel)</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-green-600/80">
                                    <i class="fas fa-hallway"></i>
                                </div>
                                <input class="block w-full pl-10 pr-4 py-3 border-2 border-green-300/50 rounded-full bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-green-500/30 focus:border-green-400 transition-all duration-300 font-medium text-gray-800 placeholder-gray-500/70 shadow-sm"
                                       id="name"
                                       type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="Nom du couloir">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-8">
                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold rounded-full shadow-lg transition-all duration-300 hover:shadow-xl flex items-center backdrop-blur-md">
                            <i class="fas fa-plus-circle mr-2"></i> 
                            <span>Créer le couloir</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced blur effects */
.backdrop-blur-xl {
    backdrop-filter: blur(24px);
}
.backdrop-blur-lg {
    backdrop-filter: blur(16px);
}
.backdrop-blur-md {
    backdrop-filter: blur(12px);
}
.backdrop-blur-sm {
    backdrop-filter: blur(8px);
}

/* Background transparency levels */
.bg-white\/20 {
    background-color: rgba(255, 255, 255, 0.2);
}
.bg-white\/40 {
    background-color: rgba(255, 255, 255, 0.4);
}
.bg-white\/70 {
    background-color: rgba(255, 255, 255, 0.7);
}
.bg-white\/90 {
    background-color: rgba(255, 255, 255, 0.9);
}

/* Border transparency */
.border-white\/20 {
    border-color: rgba(255, 255, 255, 0.2);
}
.border-white\/30 {
    border-color: rgba(255, 255, 255, 0.3);
}
.border-green-300\/50 {
    border-color: rgba(134, 239, 172, 0.5);
}

/* Gradient text effect */
.bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}
.from-green-600 {
    --tw-gradient-from: #16a34a;
}
.to-green-700 {
    --tw-gradient-to: #15803d;
}
.to-green-800 {
    --tw-gradient-to: #166534;
}

/* Button hover states */
.hover\:from-green-700:hover {
    --tw-gradient-from: #15803d;
}
.hover\:to-green-800:hover {
    --tw-gradient-to: #166534;
}

/* Transition effects */
.transition-all {
    transition-property: all;
}
.duration-300 {
    transition-duration: 300ms;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .backdrop-blur-xl {
        backdrop-filter: blur(16px);
    }
    .backdrop-blur-lg {
        backdrop-filter: blur(12px);
    }
    
    /* Make inputs slightly less rounded on mobile */
    .rounded-full {
        border-radius: 0.75rem;
    }
}
</style>
@endsection