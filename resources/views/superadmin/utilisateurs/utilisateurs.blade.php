@extends('layouts.app')

@section('content')

<!-- CSRF Token Meta Tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-4 md:p-6 pb-16">
        <!-- Users List Section -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-2xl p-4 md:p-8 w-full mx-auto mt-4 md:mt-8 transition-all duration-500 transform hover:scale-[1.005]">
            <!-- Header with actions -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-2xl md:text-4xl font-extrabold text-gray-900 animate-fadeIn">Liste des Utilisateurs</h1>
                @if(auth()->user()->role === 'superadmin')
                    <a href="{{ route('superadmin.utilisateurs.create') }}"
                       class="btn btn-primary transform hover:scale-105 transition-transform duration-300 animate-bounceIn">
                       <i class="fas fa-plus-circle mr-2"></i>Créer un compte
                    </a>
                @endif
            </div>

            <!-- Users Table -->
            <div class="overflow-x-auto w-full backdrop-filter backdrop-blur-lg bg-blue-900/30 rounded-xl shadow-lg border border-blue-800/20 animate-fadeInUp">
                <table class="w-full border-collapse">
                    <!-- Search headers row -->
                    <thead class="bg-blue-900/20 backdrop-blur-sm">
                        <tr class="animate-fadeIn">
                            <!-- ID Search -->
                            <th class="px-2 py-2">
                                <div class="relative w-24 mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-blue-600 text-xs"></i>
                                    </div>
                                    <input type="text"
                                           class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-blue-400 rounded-full bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                           placeholder="ID"
                                           id="search-id"
                                           data-column="0" />
                                </div>
                            </th>
                           
                            <!-- Name Search -->
                            <th class="px-2 py-2">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-blue-600 text-xs"></i>
                                    </div>
                                    <input type="text"
                                           class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-blue-400 rounded-full bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                           placeholder="Nom"
                                           id="search-name"
                                           data-column="1" />
                                </div>
                            </th>
                           
                            <!-- Email Search -->
                            <th class="px-2 py-2">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-blue-600 text-xs"></i>
                                    </div>
                                    <input type="text"
                                           class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-blue-400 rounded-full bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                           placeholder="Email"
                                           id="search-email"
                                           data-column="2" />
                                </div>
                            </th>
                           
                            <!-- Role Search -->
                            <th class="px-2 py-2">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-user-tag text-blue-600 text-xs"></i>
                                    </div>
                                    <select class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-blue-400 rounded-full bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800 appearance-none"
                                            id="search-role"
                                            data-column="3">
                                        <option value="">Rôle</option>
                                        <option value="admin">Admin</option>
                                        <option value="superadmin">Superadmin</option>
                                        <option value="leader">Leader</option>
                                        <option value="utilisateur">Utilisateur</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <i class="fas fa-chevron-down text-blue-600 text-xs"></i>
                                    </div>
                                </div>
                            </th>
                           
                            <!-- Site Search -->
                            <th class="px-2 py-2">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-building text-blue-600 text-xs"></i>
                                    </div>
                                    <input type="text"
                                           class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-blue-400 rounded-full bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                                           placeholder="Site"
                                           id="search-site"
                                           data-column="4" />
                                </div>
                            </th>
                           
                            <!-- Branche Search -->
                            <th class="px-2 py-2">
                                <div class="relative mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-code-branch text-blue-600 text-xs"></i>
                                    </div>
                                    <select class="block w-full pl-8 pr-2 py-1 text-sm border-2 border-blue-400 rounded-full bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800 appearance-none"
                                            id="search-branche"
                                            data-column="5">
                                        <option value="">Branche</option>
                                        <option value="carburant">Carburant</option>
                                        <option value="commercial">Commercial</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <i class="fas fa-chevron-down text-blue-600 text-xs"></i>
                                    </div>
                                </div>
                            </th>
                           
                            <!-- Actions header (empty) -->
                            <th class="px-4 py-3 text-right text-sm md:text-base font-bold text-white uppercase tracking-wider">
                                @if(auth()->user()->role === 'superadmin')
                                    Actions
                                @endif
                            </th>
                        </tr>
                    </thead>
                   
                    <!-- Column headers -->
                    <thead class="bg-blue-900/70 backdrop-blur-sm">
                        <tr class="animate-fadeIn">
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">Nom</th>
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">Rôle</th>
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">Site</th>
                            <th class="px-4 py-3 text-left text-sm md:text-base font-bold text-white uppercase tracking-wider">Branche</th>
                            @if(auth()->user()->role === 'superadmin')
                                <th class="px-4 py-3 text-right text-sm md:text-base font-bold text-white uppercase tracking-wider">Actions</th>
                            @endif
                        </tr>
                    </thead>
                   
                    <tbody class="divide-y divide-blue-800/30" id="usersTable">
                        @foreach($users as $index => $user)
                        <tr class="hover:bg-blue-900/20 transition-all duration-300 ease-in-out transform hover:translate-x-1 animate-fadeIn" style="animation-delay: {{ $index * 50 }}ms">
                            <td class="px-4 py-3 text-gray-900">{{ $user->id }}</td>
                            <td class="px-4 py-3 text-gray-900">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-gray-900">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-blue-700 text-white',
                                        'superadmin' => 'bg-purple-700 text-white',
                                        'leader' => 'bg-green-700 text-white',
                                        'utilisateur' => 'bg-yellow-600 text-white',
                                    ];
                                    $roleClass = $roleColors[$user->role] ?? 'bg-gray-700 text-white';
                                @endphp
                                <span class="px-3 py-1.5 text-xs md:text-sm font-bold rounded-full shadow-md {{ $roleClass }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-900">{{ $user->site->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-900">
                                @php
                                    $brancheColors = [
                                        'carburant' => 'bg-orange-600 text-white',
                                        'commercial' => 'bg-indigo-600 text-white',
                                    ];
                                    $brancheClass = $brancheColors[$user->branche->name ?? ''] ?? 'bg-gray-600 text-white';
                                @endphp
                                <span class="px-3 py-1.5 text-xs md:text-sm font-bold rounded-full shadow-md {{ $brancheClass }}">
                                    {{ ucfirst($user->branche->name ?? 'N/A') }}
                                </span>
                            </td>
                            @if(auth()->user()->role === 'superadmin')
                                <td class="px-4 py-3 text-right space-x-2 md:space-x-3">
                                    <button onclick="openRoleModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')"
                                            class="text-indigo-600 hover:text-indigo-800 transform hover:scale-110 transition duration-200 bg-white/60 hover:bg-indigo-100 px-3 py-1.5 rounded-lg font-bold">
                                        <i class="fas fa-user-tag mr-1"></i>
                                        <span class="hidden md:inline">Rôle</span>
                                    </button>
                                    <button onclick="openDeleteModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}')"
                                            class="text-red-600 hover:text-red-800 transform hover:scale-110 transition duration-200 bg-white/60 hover:bg-red-100 px-3 py-1.5 rounded-lg font-bold">
                                        <i class="fas fa-trash-alt mr-1"></i>
                                        <span class="hidden md:inline">Supprimer</span>
                                    </button>
                                </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@if(auth()->user()->role === 'superadmin')
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md">
        <h3 class="text-xl font-bold text-red-600 mb-4">Confirmer la suppression</h3>
        <p class="text-gray-700 mb-2">Êtes-vous sûr de vouloir supprimer l'utilisateur :</p>
        <p class="text-gray-900 font-semibold mb-1" id="user-to-delete-name"></p>
        <p class="text-gray-700 mb-1" id="user-to-delete-email"></p>
        <p class="text-red-500 text-sm mb-6">Attention: Cette action est irréversible.</p>
       
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="user_id" id="delete-user-id">
           
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Annuler
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Confirmer la suppression
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Role Update Modal -->
@if(auth()->user()->role === 'superadmin')
<div id="roleModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md">
        <h3 class="text-xl font-bold text-blue-600 mb-4">Modifier le rôle</h3>
        <p class="text-gray-700 mb-2">Sélectionnez le nouveau rôle pour :</p>
        <p class="text-gray-900 font-semibold mb-1" id="user-to-update-name"></p>
        <p class="text-gray-700 mb-1" id="user-to-update-email"></p>
        
        <form id="roleForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="user_id" id="update-user-id">
            
            <div class="space-y-4 mb-6">
                <div class="flex items-center">
                    <input id="role-superadmin" name="role" type="radio" value="superadmin" 
                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                    <label for="role-superadmin" class="ml-3 block text-sm font-medium text-gray-700">
                        Superadmin
                    </label>
                </div>
                <div class="flex items-center">
                    <input id="role-admin" name="role" type="radio" value="admin" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                    <label for="role-admin" class="ml-3 block text-sm font-medium text-gray-700">
                        Admin
                    </label>
                </div>
                <div class="flex items-center">
                    <input id="role-leader" name="role" type="radio" value="leader" 
                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                    <label for="role-leader" class="ml-3 block text-sm font-medium text-gray-700">
                        Leader
                    </label>
                </div>
                <div class="flex items-center">
                    <input id="role-utilisateur" name="role" type="radio" value="utilisateur" 
                           class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300">
                    <label for="role-utilisateur" class="ml-3 block text-sm font-medium text-gray-700">
                        Utilisateur
                    </label>
                </div>
            </div>
           
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeRoleModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Annuler
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<style>
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes bounceIn { 0% { opacity: 0; transform: scale(0.8); } 50% { opacity: 1; transform: scale(1.05); } 100% { transform: scale(1); } }
@keyframes slideInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }

.animate-fadeIn { animation: fadeIn 0.6s ease-out forwards; }
.animate-fadeInUp { animation: fadeInUp 0.6s ease-out forwards; }
.animate-bounceIn { animation: bounceIn 0.6s ease-out forwards; }
.animate-slideInDown { animation: slideInDown 0.4s ease-out forwards; }

/* Radio button styles */
input[type="radio"] {
    border-color: #6366f1;
}
input[type="radio"]:checked {
    background-color: currentColor;
    border-color: currentColor;
}
</style>

<script>
// Delete Modal Functions
function openDeleteModal(userId, userName, userEmail) {
    const modal = document.getElementById('deleteModal');
    const userIdInput = document.getElementById('delete-user-id');
    const userNameSpan = document.getElementById('user-to-delete-name');
    const userEmailSpan = document.getElementById('user-to-delete-email');
   
    userIdInput.value = userId;
    userNameSpan.textContent = userName;
    userEmailSpan.textContent = userEmail;
   
    // Set the form action
    document.getElementById('deleteForm').action = `/superadmin/utilisateurs/${userId}`;
   
    modal.classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Role Modal Functions
function openRoleModal(userId, userName, userEmail, currentRole) {
    const modal = document.getElementById('roleModal');
    const userIdInput = document.getElementById('update-user-id');
    const userNameSpan = document.getElementById('user-to-update-name');
    const userEmailSpan = document.getElementById('user-to-update-email');
   
    userIdInput.value = userId;
    userNameSpan.textContent = userName;
    userEmailSpan.textContent = userEmail;
   
    // Set the form action to use the named route
    document.getElementById('roleForm').action = `/superadmin/utilisateurs/${userId}`;
    
    // Uncheck all radio buttons first
    document.querySelectorAll('input[name="role"]').forEach(radio => {
        radio.checked = false;
    });
    
    // Check the current role radio button
    const currentRoleRadio = document.querySelector(`input[name="role"][value="${currentRole}"]`);
    if (currentRoleRadio) {
        currentRoleRadio.checked = true;
    }
   
    modal.classList.remove('hidden');
}

function closeRoleModal() {
    document.getElementById('roleModal').classList.add('hidden');
}

// Handle role form submission
document.getElementById('roleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST', // Laravel will handle the PUT via method spoofing
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close the modal
            closeRoleModal();
            // Reload the page to see changes
            window.location.reload();
        } else {
            alert('Error updating role: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the role');
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Get all search inputs
    const searchInputs = document.querySelectorAll('input[id^="search-"], select[id^="search-"]');
    const rows = document.querySelectorAll('#usersTable tr');

    // Function to filter rows based on all search criteria
    function filterRows() {
        const filters = {};
       
        // Collect all filter values
        searchInputs.forEach(input => {
            const column = input.getAttribute('data-column');
            filters[column] = input.value.toLowerCase();
        });

        rows.forEach(row => {
            let visible = true;
            const cells = row.cells;

            // Check each filter
            for (const [column, value] of Object.entries(filters)) {
                if (value === '') continue;
               
                const cell = cells[column];
                let cellText = cell.textContent.toLowerCase();
               
                // Special handling for role and branche columns (span content)
                if (column === '3' || column === '5') {
                    const span = cell.querySelector('span');
                    if (span) {
                        cellText = span.textContent.toLowerCase();
                    }
                }
               
                if (!cellText.includes(value)) {
                    visible = false;
                    break;
                }
            }

            // Apply visibility
            if (visible) {
                row.classList.remove('hidden');
                row.style.opacity = 1;
                row.style.transform = 'translateY(0)';
            } else {
                row.style.opacity = 0;
                row.style.transform = 'translateY(-10px)';
                setTimeout(() => row.classList.add('hidden'), 300);
            }
        });
    }

    // Add event listeners to all search inputs
    searchInputs.forEach(input => {
        input.addEventListener('input', filterRows);
    });

    // Responsive data labels
    const headers = document.querySelectorAll('thead th');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        cells.forEach((cell, index) => {
            if (index < headers.length) {
                cell.setAttribute('data-label', headers[index].textContent);
            }
        });
    });
});
</script>
@endsection