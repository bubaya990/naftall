@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        <!-- Users List Section -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-2xl p-4 md:p-8 max-w-3xl w-full mx-auto mt-4 md:mt-8 transition-all duration-500 transform hover:scale-[1.005]">
            <!-- Header with actions -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <a href="{{ route('superadmin.utilisateurs.store') }}" 
                   class="btn btn-primary transform hover:scale-105 transition-transform duration-300">
                   <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                </a>
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

            <form method="POST" action="{{ route('superadmin.utilisateurs.store') }}" class="mt-4 space-y-6">
                @csrf

                <!-- Form container -->
                <div class="overflow-x-auto w-full backdrop-filter backdrop-blur-lg bg-blue-900/30 rounded-xl shadow-lg border border-blue-800/20">
                    <div class="grid grid-cols-1 md:grid-cols gap-6 p-6">
                        <!-- Full Name -->
                        <div class="col-span-1">
                            <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="name">Nom complet </label>
                            <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                   id="name"
                                   type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   autofocus
                                   placeholder="Nom et prénom"
                                   >
                        </div>

                        <!-- Email -->
                        <div class="col-span-1">
                            <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="email">Email </label>
                            <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                   id="email"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   placeholder="@example.com"
                                   >
                        </div>

                        <!-- Password -->
                        <div class="col-span-1">
                            <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="password">Mot de passe </label>
                            <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                   id="password"
                                   type="password"
                                   name="password"
                                    placeholder="Mot de passe"
                                   required>
                        </div>

                         <!-- Password Confirmation -->
                         <div class="col-span-1">
                            <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="password_confirmation">Confirmer le mot de passe </label>
                            <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                   id="password_confirmation"
                                   type="password"
                                   name="password_confirmation"
                                   placeholder="Confirmer le mot de passe"
                                   required>
                        </div>

                       

                        <!-- Role -->
                        <div class="col-span-1">
                            <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="role">Rôle </label>
                            <select id="role" name="role"
                                    class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                    required>
                                <option value="">Sélectionnez un rôle</option>
                                <option value="utilisateur" {{ old('role') == 'utilisateur' ? 'selected' : '' }}>Utilisateur</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="leader" {{ old('role') == 'leader' ? 'selected' : '' }}>Leader</option>
                                <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                            </select>
                        </div>
 

                        <!-- Site -->
                        <div class="col-span-1">
                            <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="site_id">Site </label>
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

<!-- Branche Selection -->
<select name="branche_id" id="branche_id" class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800" required>
    <option value="">Sélectionnez une branche</option>
    <option value="1" {{ old('branche_id') == 1 ? 'selected' : '' }}>Commercial</option>
    <option value="2" {{ old('branche_id') == 2 ? 'selected' : '' }}>Carburant</option>
</select>



                      
                       
                    </div>
                </div>

                <!-- Submit button -->
                <div class="flex justify-end mt-6">
                    <button type="submit"
                            class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition duration-300 transform hover:scale-105">
                        Créer l'utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const siteSelect = document.getElementById('site_id');
    const brancheDisplay = document.getElementById('branche-display');
    const brancheInput = document.getElementById('branche_name');

    const sites = {
        @foreach($sites as $site)
            {{ $site->id }}: '{{ ucfirst($site->branche) }}',
        @endforeach
    };

    function updateBranche(siteId) {
        const branche = sites[siteId] || 'Sélectionnez un site pour voir sa branche';
        brancheDisplay.textContent = branche;
        brancheInput.value = branche;
    }

    siteSelect.addEventListener('change', function() {
        updateBranche(this.value);
    });

    if (siteSelect.value) {
        updateBranche(siteSelect.value);
    }
});




</script>
@endsection