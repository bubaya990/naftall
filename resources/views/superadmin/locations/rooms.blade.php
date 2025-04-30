@extends('layouts.app')

@section('content')
<!-- CSRF Token Meta Tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>
    
    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-6 pb-16">
        <!-- Dashboard content with glassmorphism effect -->
        <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-8 max-w-7xl mx-auto mt-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-900">Rooms Management</h1>
                    <p class="text-gray-600 mt-1">Location: {{ $rooms->first()->location->name ?? 'N/A' }}</p>
                </div>
                
                <!-- Create button -->
                <div>
                    <a href="{{ route('superadmin.locations.addroom', ['location' => $locationId]) }}" 
                       class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 inline-flex items-center">
                        <i class="fas fa-plus-circle mr-2"></i> Add New Room
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Rooms Table -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg overflow-hidden">
                @if($rooms->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-blue-50/50">
                            <tr>
                                <th class="px-6 py-3 text-blue-900 font-medium">Name</th>
                                <th class="px-6 py-3 text-blue-900 font-medium">Code</th>
                                <th class="px-6 py-3 text-blue-900 font-medium">Type</th>
                                <th class="px-6 py-3 text-blue-900 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/50">
                            @foreach($rooms as $room)
                            <tr class="hover:bg-blue-50/30 transition-colors duration-200">
                                <td class="px-6 py-4 font-medium">{{ $room->name }}</td>
                                <td class="px-6 py-4">{{ $room->code }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <span id="current-type-{{ $room->id }}">{{ $room->type }}</span>
                                        <button onclick="openTypeModal('{{ $room->id }}', '{{ $room->type }}')" 
                                                class="text-blue-600 hover:text-blue-800 transition-colors duration-200 ml-2">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
    <div class="flex space-x-3">
        <a href="{{ route('superadmin.locations.rooms.materials', ['location' => $locationId, 'room' => $room->id]) }}" 
           class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
            <i class="fas fa-eye mr-1"></i> Voir Matériel
        </a>
        <form action="{{ route('superadmin.locations.destroyRoom', ['location' => $locationId, 'room' => $room->id]) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Are you sure you want to delete this room?')" 
                    class="text-red-600 hover:text-red-800 transition-colors duration-200">
                <i class="fas fa-trash-alt mr-1"></i> Delete
            </button>
        </form>
    </div>
</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <i class="fas fa-door-open text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-500">No rooms found</h3>
                    <p class="text-gray-400 mt-1">Add your first room using the button above</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Type Change Modal -->
<div id="typeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md">
        <h3 class="text-xl font-bold text-blue-900 mb-4">Change Room Type</h3>
        
        <form id="typeChangeForm">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="room_id" id="modal-room-id">
            
            <div class="space-y-3 mb-6">
                @foreach(['Bureau', 'Salle reunion', 'Salle reseau'] as $type)
                <div class="flex items-center">
                    <input type="radio" id="type-{{ $type }}" name="type" value="{{ $type }}" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                    <label for="type-{{ $type }}" class="ml-3 block text-gray-700">
                        {{ $type }}
                    </label>
                </div>
                @endforeach
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeTypeModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button type="button" id="confirmChangeBtn"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Confirm Change
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openTypeModal(roomId, currentType) {
        const modal = document.getElementById('typeModal');
        const roomIdInput = document.getElementById('modal-room-id');
        
        // Set the current room ID
        roomIdInput.value = roomId;
        
        // Uncheck all radio buttons first
        document.querySelectorAll('input[name="type"]').forEach(radio => {
            radio.checked = false;
        });
        
        // Check the current type radio button if it exists
        const currentRadio = document.querySelector(`input[name="type"][value="${currentType}"]`);
        if (currentRadio) {
            currentRadio.checked = true;
        }
        
        // Show the modal
        modal.classList.remove('hidden');
    }
    
    function closeTypeModal() {
        document.getElementById('typeModal').classList.add('hidden');
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Handle confirm button click
        document.getElementById('confirmChangeBtn').addEventListener('click', function() {
            const form = document.getElementById('typeChangeForm');
            const formData = new FormData(form);
            const roomId = document.getElementById('modal-room-id').value;
            const submitButton = this;
            
            if (!form.querySelector('input[name="type"]:checked')) {
                alert('Please select a room type');
                return;
            }
            
            // Disable button during submission
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
            
            fetch(`/superadmin/locations/{{ $locationId }}/rooms/${roomId}/update-type`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Update the displayed type
                const newType = form.querySelector('input[name="type"]:checked').value;
                document.getElementById(`current-type-${roomId}`).textContent = newType;
                
                closeTypeModal();
                
                // Show success message
                alert('Room type updated successfully!');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating room type: ' + error.message);
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Confirm Change';
            });
        });
    });
</script>
@endsection