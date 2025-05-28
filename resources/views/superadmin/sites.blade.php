@extends('layouts.app')

@section('content')

<!-- Blurred Background -->
<div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/naftalBG.jpg'); filter: blur(6px);"></div>

<div class="p-6 relative z-10 max-w-7xl mx-auto">
    
    <!-- Title with white background -->
    <div class="mb-8 bg-white p-6 rounded-xl shadow-lg">

        <!-- Back button -->
    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="flex items-center text-blue-600 hover:text-blue-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Retour
        </a>
    </div>
        <h1 class="text-3xl font-bold text-blue-900">
            {{ $site->name }} – {{ ucfirst($branche->name) }}
        </h1>
        <p class="text-gray-600 mt-2">
            @if(auth()->user()->role === 'superadmin')
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Mode Superadmin</span>
            @elseif(auth()->user()->role === 'admin' && auth()->user()->site_id == $site->id)
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm"> Mode Admin</span>
            @else
                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">Mode Visualisation</span>
            @endif
        </p>
    </div>

   <!-- Upload Plans Form - Only for superadmin -->
@if(auth()->user()->role === 'superadmin')
<div class="mb-8 bg-white p-6 rounded-xl shadow-lg border border-blue-200">
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('branches.upload.plans', $branche->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block text-blue-800 font-bold mb-2 text-lg"> Téléverser des images de plan</label>
            <div class="space-y-4">
                <input type="file" name="plans[]" multiple class="block w-full text-sm text-gray-700
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100" required>
                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition shadow-md flex items-center justify-center gap-2 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Importer
                </button>
            </div>
        </div>
    </form>
</div>
@endif
    <!-- Display Plans Dynamically -->
    @if (!empty($branche->plan_images))
        <div class="@if(count($branche->plan_images) === 1) flex justify-center @else grid grid-cols-1 md:grid-cols-2 @endif gap-8">
            @foreach ($branche->plan_images as $index => $image)
                <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-200 hover:shadow-2xl transition duration-300 relative @if(count($branche->plan_images) === 1) max-w-2xl @endif">
                    <div class="p-1 bg-gradient-to-r from-blue-500 to-blue-700"></div>
                    
                    <div class="p-4">
                        <div class="relative rounded-lg overflow-hidden border border-gray-300 bg-gray-100">
                            <img src="{{ Storage::url($image) }}" 
                                alt="Plan Image {{ $index + 1 }}"
                                class="w-full h-auto object-contain max-h-80 mx-auto">
                            
                          <!-- Display links for this image -->
@foreach ($branche->plan_links ?? [] as $key => $link)
    @if ($link['image_index'] == $index)
        @if(auth()->user()->role === 'superadmin' || (auth()->user()->role === 'admin' && auth()->user()->site_id == $site->id))
            <div class="absolute transform -translate-x-1/2 -translate-y-1/2 group" 
                 style="left: {{ $link['x'] }}%; top: {{ $link['y'] }}%;">
                <a href="{{ $link['url'] }}" 
                   class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg hover:bg-blue-700 transition relative z-20">
                    {{ $loop->iteration }}
                </a>
                
                @if(auth()->user()->role === 'superadmin')
                <form method="POST" 
                      action="{{ route('branches.delete.link', ['branche' => $branche->id, 'linkIndex' => $key]) }}" 
                      class="absolute -top-2 -right-2 z-30 opacity-0 group-hover:opacity-100 transition-opacity">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs shadow-md hover:bg-red-600"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce lien ?')">
                        ×
                    </button>
                </form>
                @endif
            </div>
        @endif
    @endif
@endforeach
                        </div>
                        
                        <div class="mt-4 flex justify-between items-center">
                            <h2 class="text-xl font-bold text-blue-800">Plan {{ $index + 1 }}</h2>
                            
                            <!-- Action buttons - Only for superadmin -->
                            @if(auth()->user()->role === 'superadmin')
                            <div class="flex gap-2">
                                <button 
                                    onclick="console.log('Button clicked'); openImageModal('{{ Storage::url($image) }}', {{ $index }})"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l1.5-1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                                    </svg>
                                    Ajouter un lien
                                </button>
                                
                                <form action="{{ route('branches.delete.plan', ['branche' => $branche->id, 'imageIndex' => $index]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600 transition shadow" onclick="return confirm('Are you sure you want to delete this plan?')" title="Supprimer le plan ">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center bg-white p-8 rounded-xl shadow-lg border border-blue-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="text-xl font-semibold text-gray-600 mt-4">Aucune image de plan n’a encore été téléchargée</h3>
            <p class="text-gray-500 mt-2">
                @if(auth()->user()->role === 'superadmin')
                Téléversez votre premier plan pour commencer
                @else
                Veuillez contacter le superadmin pour téléverser des plans
                @endif
            </p>
        </div>
    @endif
</div>

<!-- Modal for Image Selection -->

<input type="hidden" name="location_id" id="location_id_input"> <!-- Changed ID -->
@if(auth()->user()->role === 'superadmin')
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50 hidden">
    <div class="relative bg-white rounded-lg overflow-hidden w-full max-w-md mx-4 shadow-xl border border-blue-300">
        <button onclick="closeImageModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl font-bold bg-gray-100 rounded-full w-6 h-6 flex items-center justify-center">
            &times;
        </button>

        <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-3">
            <h2 class="text-lg font-semibold text-center text-white">
                Sélectionner un point sur l'image
            </h2>
        </div>

        <div class="p-3">
            <div class="relative overflow-auto border border-dashed border-gray-300 rounded-md bg-gray-50" style="max-height: 50vh;">
                <img id="modalImage" src="" alt="Plan" 
                     class="mx-auto cursor-crosshair w-full h-auto object-contain"
                     onclick="selectPoint(event)">
                <div id="pointMarker" class="absolute w-4 h-4 bg-red-600 rounded-full border-2 border-white hidden shadow" style="transform: translate(-50%, -50%);"></div>
            </div>

            <form id="pointForm" method="POST" action="{{ route('branches.store.point') }}" class="mt-3 space-y-2">
                @csrf
                <input type="hidden" name="branche_id" value="{{ $branche->id }}">
                <input type="hidden" name="image_index" id="imageIndex">
                <input type="hidden" name="x" id="pointX">
                <input type="hidden" name="y" id="pointY">

                <!-- Location Select -->
                <div class="mb-2">
                    <label for="location_id" class="block text-gray-700 text-sm font-medium mb-1">Emplacement</label>
                    <select name="location_id" id="location_id" 
                            class="form-select block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-1 px-2 border"
                            required
                            onchange="loadLocationData(this.value)">
                        <option value="">Sélectionner un emplacement</option>
                        @foreach($branche->site->locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Link Type Selection -->
                <div class="bg-blue-50 p-2 rounded-md">
                    <label class="block text-blue-800 text-sm font-medium mb-1">Type de lien</label>
                    <div class="flex gap-3 text-sm">
                        <label class="inline-flex items-center">
                            <input type="radio" name="link_type" value="room" checked class="form-radio h-4 w-4 text-blue-600">
                            <span class="ml-1 text-gray-700">Salle</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="link_type" value="corridor" class="form-radio h-4 w-4 text-blue-600">
                            <span class="ml-1 text-gray-700">Couloir</span>
                        </label>
                    </div>
                </div>

             <!-- Room Select -->
<div class="mb-2" id="roomSelectContainer">
    <label for="room_id" class="block text-gray-700 text-sm font-medium mb-1">Salle</label>
    <select name="room_id" id="room_id" class="form-select block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 border">
        <option value="">Sélectionnez d'abord un emplacement</option>
    </select>
</div>

<!-- Corridor Select -->
<div class="mb-2 hidden" id="corridorSelectContainer">
    <label for="corridor_id" class="block text-gray-700 text-sm font-medium mb-1">Couloir</label>
    <select name="corridor_id" id="corridor_id" class="form-select block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 border">
        <option value="">Sélectionnez d'abord un emplacement</option>
    </select>
</div>

                <!-- Form Buttons -->
                <div class="flex justify-end gap-2 mt-3">
                    <button type="button" onclick="closeImageModal()" class="text-sm bg-gray-300 text-gray-700 px-3 py-1 rounded-md hover:bg-gray-400 transition">
                        Annuler
                    </button>
                    <button type="submit" class="text-sm bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
    // Global variables
    let selectedX = 0;
    let selectedY = 0;
    let currentImageIndex = 0;

    // Debug helper
    function debugLog(message, data = null) {
        console.log(`[DEBUG] ${message}`, data);
    }

    // Error handler
    window.addEventListener('error', function(e) {
        console.error('Global error:', e.message, 'in', e.filename, 'line', e.lineno);
        showAlert(`JavaScript Error: ${e.message}`, 'red');
    });

    // Alert display
    function showAlert(message, type = 'blue') {
        const colors = {
            blue: 'bg-blue-500',
            green: 'bg-green-500',
            red: 'bg-red-500',
            yellow: 'bg-yellow-500'
        };

        const alertDiv = document.createElement('div');
        alertDiv.className = `fixed top-4 right-4 p-4 text-white rounded-lg shadow-lg ${colors[type] || colors.blue} z-[99999]`;
        alertDiv.innerHTML = message;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    // Modal functions
    function openImageModal(imagePath, imageIndex) {
        debugLog('Opening modal', {imagePath, imageIndex});
        
        try {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const marker = document.getElementById('pointMarker');

            if (!modal || !modalImage || !marker) {
                throw new Error('Required modal elements not found');
            }

            // Reset form
            document.getElementById('room_id').innerHTML = '<option value="">Sélectionnez un emplacement</option>';
            document.getElementById('corridor_id').innerHTML = '<option value="">Sélectionnez un emplacement</option>';
            marker.classList.add('hidden');

            // Set image
            modalImage.src = imagePath;
            currentImageIndex = imageIndex;
            
            // Show modal
            modal.classList.remove('hidden');
            debugLog('Modal opened successfully');

            // Image load handlers
            modalImage.onload = () => {
                modalImage.style.cursor = 'crosshair';
                debugLog('Image loaded successfully');
            };

            modalImage.onerror = () => {
                throw new Error(`Failed to load image: ${imagePath}`);
            };

        } catch (error) {
            console.error('Error in openImageModal:', error);
            showAlert(`Failed to open modal: ${error.message}`, 'red');
        }
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        if (modal) {
            modal.classList.add('hidden');
            debugLog('Modal closed');
        }
    }

    function selectPoint(event) {
        try {
            debugLog('Selecting point', event);
            
            const image = event.target;
            const marker = document.getElementById('pointMarker');
            
            if (!image || !marker) {
                throw new Error('Image or marker element not found');
            }

            const rect = image.getBoundingClientRect();
            const xPercent = ((event.clientX - rect.left) / rect.width) * 100;
            const yPercent = ((event.clientY - rect.top) / rect.height) * 100;

            selectedX = xPercent.toFixed(2);
            selectedY = yPercent.toFixed(2);

            // Update marker position
            marker.style.left = `${selectedX}%`;
            marker.style.top = `${selectedY}%`;
            marker.classList.remove('hidden');

            // Update form fields
            document.getElementById('pointX').value = selectedX;
            document.getElementById('pointY').value = selectedY;
            document.getElementById('imageIndex').value = currentImageIndex;

            debugLog('Point selected', {x: selectedX, y: selectedY});

        } catch (error) {
            console.error('Error in selectPoint:', error);
            showAlert(`Failed to select point: ${error.message}`, 'red');
        }
    }

    // Location data loading
    function loadLocationData(locationId) {
        debugLog('Loading location data', {locationId});
        
        if (!locationId) {
            document.getElementById('room_id').innerHTML = '<option value="">Sélectionnez d\'abord un emplacement</option>';
            document.getElementById('corridor_id').innerHTML = '<option value="">Sélectionnez d\'abord un emplacement</option>';
            return;
        }

        // Load rooms
        fetch(`/superadmin/locations/${locationId}/rooms-json`)
            .then(res => {
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                return res.json();
            })
            .then(rooms => {
                const roomSelect = document.getElementById('room_id');
                roomSelect.innerHTML = rooms.length
                    ? rooms.map(r => `<option value="${r.id}">${r.name} (${r.code})</option>`).join('')
                    : '<option value="">Aucune salle trouvée</option>';
                debugLog('Rooms loaded', rooms);
            })
            .catch(error => {
                console.error('Error loading rooms:', error);
                document.getElementById('room_id').innerHTML = '<option value="">Erreur de chargement</option>';
                showAlert('Failed to load rooms', 'red');
            });

        // Load corridors
        fetch(`/superadmin/locations/${locationId}/corridors-json`)
            .then(res => {
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                return res.json();
            })
            .then(corridors => {
                const corridorSelect = document.getElementById('corridor_id');
                corridorSelect.innerHTML = corridors.length
                    ? corridors.map(c => `<option value="${c.id}">${c.name}</option>`).join('')
                    : '<option value="">Aucun couloir trouvé</option>';
                debugLog('Corridors loaded', corridors);
            })
            .catch(error => {
                console.error('Error loading corridors:', error);
                document.getElementById('corridor_id').innerHTML = '<option value="">Erreur de chargement</option>';
                showAlert('Failed to load corridors', 'red');
            });
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        debugLog('DOM fully loaded');

        // Link type toggle
        document.querySelectorAll('input[name="link_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const isRoom = this.value === 'room';
                document.getElementById('roomSelectContainer').classList.toggle('hidden', !isRoom);
                document.getElementById('corridorSelectContainer').classList.toggle('hidden', isRoom);
                debugLog('Link type changed', this.value);
            });
        });

        // Location change handler
        const locationSelect = document.getElementById('location_id');
        if (locationSelect) {
            locationSelect.addEventListener('change', function() {
                debugLog('Location changed', this.value);
                loadLocationData(this.value);
            });
        }

        // Form submission
        const pointForm = document.getElementById('pointForm');
        if (pointForm) {
            pointForm.addEventListener('submit', function(e) {
                e.preventDefault();
                debugLog('Form submission started');
                
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Enregistrement...
                `;

                const formData = new FormData(this);
                debugLog('Form data', Object.fromEntries(formData.entries()));

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    const data = await response.json();
                    debugLog('Server response', data);
                    
                    if (!response.ok) {
                        throw new Error(data.message || 'Server error');
                    }
                    
                    showAlert(data.message || 'Point enregistré avec succès', 'green');
                    setTimeout(() => window.location.reload(), 1500);
                })
                .catch(error => {
                    console.error('Submission error:', error);
                    showAlert(`Erreur: ${error.message}`, 'red');
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                });
            });
        }
    });
</script>

<style>
#imageModal {
    z-index: 99999 !important;
    background: rgba(0,0,0,0.9) !important;
}
#modalImage {
    max-height: 70vh;
    object-fit: contain;
}

#pointMarker {
    pointer-events: none;
    z-index: 10000;
}
</style>

@endsection