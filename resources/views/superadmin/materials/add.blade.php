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
                        Ajouter un 
                        @if($type == 'computers') Ordinateur
                        @elseif($type == 'printers') Imprimante
                        @elseif($type == 'ip-phones') Téléphone IP
                        @elseif($type == 'hotspots') Hotspot
                        @endif
                    </span>
                </h1>
                <a href="{{ route('superadmin.materials.list', $type) }}" 
 class="btn btn-primary transform hover:scale-105 transition-transform duration-300 ">                   <i class="fas fa-arrow-left mr-2"></i>Retour
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

            <form method="POST" action="{{ route('superadmin.materials.store', $type) }}" class="mt-4 space-y-6">
                @csrf

                <!-- Form container with glass effect -->
                <div class="overflow-x-auto w-full backdrop-filter backdrop-blur-sm bg-white/50 rounded-xl shadow-inner border-2 border-white/30">
                    <div class=" grid-cols-1 md:grid-cols-2 gap-6 p-6">
                        <!-- Inventory Number -->
                        <div class="col-span-1 animate-float" style="animation-delay: 0.1s">
                            <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="inventory_number">N° Inventaire *</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                    <i class="fas fa-barcode"></i>
                                </div>
                                <input class="block w-full pl-10 pr-4 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 placeholder-gray-500/70 shadow-sm group-hover:shadow-md"
                                       id="inventory_number"
                                       type="text"
                                       name="inventory_number"
                                       value="{{ old('inventory_number') }}"
                                       required
                                       placeholder="Numéro d'inventaire">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-xs text-blue-600 font-semibold">Requis</span>
                                </div>
                            </div>
                        </div>

                        <!-- Serial Number -->
                        <div class="col-span-1 animate-float" style="animation-delay: 0.2s">
                            <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="serial_number">N° Série *</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                    <i class="fas fa-hashtag"></i>
                                </div>
                                <input class="block w-full pl-10 pr-4 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 placeholder-gray-500/70 shadow-sm group-hover:shadow-md"
                                       id="serial_number"
                                       type="text"
                                       name="serial_number"
                                       value="{{ old('serial_number') }}"
                                       required
                                       placeholder="Numéro de série">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-xs text-blue-600 font-semibold">Requis</span>
                                </div>
                            </div>
                        </div>

                        <!-- State -->
                        <div class="col-span-1 animate-float" style="animation-delay: 0.3s">
                            <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="state">État *</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                    <i class="fas fa-battery-three-quarters"></i>
                                </div>
                                <select id="state" name="state"
                                        class="block w-full pl-10 pr-8 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 appearance-none shadow-sm group-hover:shadow-md"
                                        required>
                                    @foreach($states as $state)
                                        <option value="{{ $state }}" {{ old('state') == $state ? 'selected' : '' }}>
                                            {{ ucfirst($state) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-blue-600/80">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Type-specific fields -->
                        @if($type == 'computers')
                            <!-- Computer Brand -->
                            <div class="col-span-1 animate-float" style="animation-delay: 0.4s">
                                <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="computer_brand">Marque *</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                        <i class="fas fa-laptop"></i>
                                    </div>
                                    <input class="block w-full pl-10 pr-4 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 placeholder-gray-500/70 shadow-sm group-hover:shadow-md"
                                           id="computer_brand"
                                           type="text"
                                           name="computer_brand"
                                           value="{{ old('computer_brand') }}"
                                           required
                                           placeholder="Marque de l'ordinateur">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="text-xs text-blue-600 font-semibold">Requis</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Computer Model -->
                            <div class="col-span-1 animate-float" style="animation-delay: 0.5s">
                                <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="computer_model">Modèle *</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                        <i class="fas fa-laptop-code"></i>
                                    </div>
                                    <input class="block w-full pl-10 pr-4 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 placeholder-gray-500/70 shadow-sm group-hover:shadow-md"
                                           id="computer_model"
                                           type="text"
                                           name="computer_model"
                                           value="{{ old('computer_model') }}"
                                           required
                                           placeholder="Modèle de l'ordinateur">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="text-xs text-blue-600 font-semibold">Requis</span>
                                    </div>
                                </div>
                            </div>

                            <!-- OS -->
                            <div class="col-span-1 animate-float" style="animation-delay: 0.6s">
                                <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="OS">Système d'exploitation *</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                        <i class="fab fa-windows"></i>
                                    </div>
                                    <select id="OS" name="OS"
                                            class="block w-full pl-10 pr-8 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 appearance-none shadow-sm group-hover:shadow-md"
                                            required>
                                        <option value="Windows7" {{ old('OS') == 'Windows7' ? 'selected' : '' }}>Windows 7</option>
                                        <option value="Windows8" {{ old('OS') == 'Windows8' ? 'selected' : '' }}>Windows 8</option>
                                        <option value="Windows10" {{ old('OS') == 'Windows10' ? 'selected' : '' }}>Windows 10</option>
                                        <option value="Linux" {{ old('OS') == 'Linux' ? 'selected' : '' }}>Linux</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-blue-600/80">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- RAM -->
                            <div class="col-span-1 animate-float" style="animation-delay: 0.7s">
                                <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="ram_id">RAM *</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                        <i class="fas fa-memory"></i>
                                    </div>
                                    <select id="ram_id" name="ram_id"
                                            class="block w-full pl-10 pr-8 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 appearance-none shadow-sm group-hover:shadow-md"
                                            required>
                                        <option value="">Sélectionnez la RAM</option>
                                        @foreach($rams as $ram)
                                            <option value="{{ $ram->id }}" {{ old('ram_id') == $ram->id ? 'selected' : '' }}>
                                                {{ $ram->capacity }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-blue-600/80">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                            </div>

                        @elseif($type == 'printers')
                            <!-- Printer Brand -->
                            <div class="col-span-1 animate-float" style="animation-delay: 0.4s">
                                <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="printer_brand">Marque *</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                        <i class="fas fa-print"></i>
                                    </div>
                                    <input class="block w-full pl-10 pr-4 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 placeholder-gray-500/70 shadow-sm group-hover:shadow-md"
                                           id="printer_brand"
                                           type="text"
                                           name="printer_brand"
                                           value="{{ old('printer_brand') }}"
                                           required
                                           placeholder="Marque de l'imprimante">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="text-xs text-blue-600 font-semibold">Requis</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Printer Model -->
                            <div class="col-span-1 animate-float" style="animation-delay: 0.5s">
                                <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="printer_model">Modèle *</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                        <i class="fas fa-print"></i>
                                    </div>
                                    <input class="block w-full pl-10 pr-4 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 placeholder-gray-500/70 shadow-sm group-hover:shadow-md"
                                           id="printer_model"
                                           type="text"
                                           name="printer_model"
                                           value="{{ old('printer_model') }}"
                                           required
                                           placeholder="Modèle de l'imprimante">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="text-xs text-blue-600 font-semibold">Requis</span>
                                    </div>
                                </div>
                            </div>

                        @elseif($type == 'ip-phones')
                            <!-- MAC Number -->
                            <div class="col-span-1 animate-float" style="animation-delay: 0.4s">
                                <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="mac_number">Numéro MAC *</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                        <i class="fas fa-network-wired"></i>
                                    </div>
                                    <input class="block w-full pl-10 pr-4 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 placeholder-gray-500/70 shadow-sm group-hover:shadow-md"
                                           id="mac_number"
                                           type="text"
                                           name="mac_number"
                                           value="{{ old('mac_number') }}"
                                           required
                                           placeholder="Numéro MAC du téléphone">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="text-xs text-blue-600 font-semibold">Requis</span>
                                    </div>
                                </div>
                            </div>

                        @elseif($type == 'hotspots')
                            <!-- Password -->
                            <div class="col-span-1 animate-float" style="animation-delay: 0.4s">
                                <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="password">Mot de passe *</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <input class="block w-full pl-10 pr-4 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 placeholder-gray-500/70 shadow-sm group-hover:shadow-md"
                                           id="password"
                                           type="text"
                                           name="password"
                                           value="{{ old('password') }}"
                                           required
                                           placeholder="Mot de passe du hotspot">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="text-xs text-blue-600 font-semibold">Requis</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Site -->
                        <div class="col-span-1 animate-float" style="animation-delay: 0.8s">
                            <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="site_id">Site *</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                    <i class="fas fa-building"></i>
                                </div>
                                <select id="site_id" name="site_id"
                                        class="block w-full pl-10 pr-8 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 appearance-none shadow-sm group-hover:shadow-md"
                                        required>
                                    <option value="">Sélectionnez un site</option>
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

                        <!-- Location -->
                        <div class="col-span-1 animate-float" style="animation-delay: 0.9s">
                            <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="location_id">Emplacement *</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <select id="location_id" name="location_id"
                                        class="block w-full pl-10 pr-8 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 appearance-none shadow-sm group-hover:shadow-md"
                                        required>
                                    <option value="">Sélectionnez d'abord un site</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-blue-600/80">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Location Type -->
                        <div class="col-span-1 md:col-span-2 animate-float" style="animation-delay: 1.0s">
                            <label class="block text-sm md:text-base font-bold text-gray-800 mb-2">Type d'emplacement *</label>
                            <div class="flex space-x-6 bg-white/50 rounded-xl p-3 border-2 border-blue-300/50">
                                <label class="inline-flex items-center">
                                    <input type="radio" id="room_radio" name="location_type" value="room" checked 
                                           class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-blue-300">
                                    <span class="ml-2 text-gray-800 font-medium">Salle</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" id="corridor_radio" name="location_type" value="corridor"
                                           class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-blue-300">
                                    <span class="ml-2 text-gray-800 font-medium">Couloir</span>
                                </label>
                            </div>
                        </div>

                        <!-- Room Field -->
                        <div id="room_field" class="col-span-1 animate-float" style="animation-delay: 1.1s">
                            <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="room_id">Salle</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <select id="room_id" name="room_id"
                                        class="block w-full pl-10 pr-8 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 appearance-none shadow-sm group-hover:shadow-md">
                                    <option value="">Sélectionnez d'abord un emplacement</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-blue-600/80">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Corridor Field -->
                        <div id="corridor_field" class="col-span-1 animate-float hidden" style="animation-delay: 1.1s">
                            <label class="block text-sm md:text-base font-bold text-gray-800 mb-2" for="corridor_id">Couloir</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600/80 group-hover:text-blue-700 transition-colors">
                                    <i class="fas fa-arrows-alt-h"></i>
                                </div>
                                <select id="corridor_id" name="corridor_id"
                                        class="block w-full pl-10 pr-8 py-3 border-2 border-blue-300/50 rounded-xl bg-white/70 focus:bg-white/90 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-300 font-medium text-gray-800 appearance-none shadow-sm group-hover:shadow-md">
                                    <option value="">Sélectionnez d'abord un emplacement</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-blue-600/80">
                                    <i class="fas fa-chevron-down"></i>
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
                            Enregistrer
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-white/50 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Keyframes for modern animations */


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
.animate-float {
    animation: float 4s ease-in-out infinite;
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
.hover\:from-blue-700:hover {
    --tw-gradient-from: #1d4ed8;
}

.hover\:to-indigo-800:hover {
    --tw-gradient-to: #3730a3;
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

<script>
document.addEventListener('DOMContentLoaded', function() {
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
                    locationSelect.innerHTML += `<option value="${location.id}">${location.name} (${location.type})</option>`;
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
                    // Use corridor.name if available, otherwise fall back to ID
                    const displayName = corridor.name || `Couloir ${corridor.id}`;
                    corridorSelect.innerHTML += `<option value="${corridor.id}">${displayName}</option>`;

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