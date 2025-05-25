@extends('layouts.app')

@section('content')

<!-- Blurred Background -->
<div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.webp'); filter: blur(6px);"></div>

<div class="p-6 relative z-10 max-w-7xl mx-auto">
    <!-- Title with white background -->
    <div class="mb-8 bg-white p-6 rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold text-blue-900">
            {{ $site->name }} – {{ ucfirst($branche->name) }}
        </h1>
        <p class="text-gray-600 mt-2">
            @if(auth()->user()->role === 'superadmin')
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Superadmin Mode</span>
            @elseif(auth()->user()->role === 'admin' && auth()->user()->site_id == $site->id)
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Site Admin Mode</span>
            @else
                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">Viewer Mode</span>
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
                <label class="block text-blue-800 font-bold mb-2 text-lg">Upload Plan Images</label>
                <div class="flex items-center gap-4">
                    <input type="file" name="plans[]" multiple class="block w-full text-sm text-gray-700
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100" required>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Upload
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
                            @foreach ($branche->plan_links ?? [] as $link)
                                @if ($link['image_index'] == $index)
                                    @if(auth()->user()->role === 'superadmin' || (auth()->user()->role === 'admin' && auth()->user()->site_id == $site->id))
                                        <a href="{{ $link['url'] }}" 
                                        class="absolute w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg hover:bg-blue-700 transition"
                                        style="left: {{ $link['x'] }}%; top: {{ $link['y'] }}%; transform: translate(-50%, -50%);">
                                            {{ $loop->iteration }}
                                        </a>
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
                                    onclick="openImageModal('{{ Storage::url($image) }}', {{ $index }})"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l1.5-1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                                    </svg>
                                    Add Link
                                </button>
                                
                                <form action="{{ route('branches.delete.plan', ['branche' => $branche->id, 'imageIndex' => $index]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600 transition shadow" onclick="return confirm('Are you sure you want to delete this plan?')" title="Delete Plan">
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
            <h3 class="text-xl font-semibold text-gray-600 mt-4">No plan images uploaded yet</h3>
            <p class="text-gray-500 mt-2">
                @if(auth()->user()->role === 'superadmin')
                    Upload your first plan to get started
                @else
                    Please contact superadmin to upload plans
                @endif
            </p>
        </div>
    @endif
</div>

<!-- Modal for Image Selection - Only for superadmin -->
@if(auth()->user()->role === 'superadmin')
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50 hidden">
    <div class="relative bg-white rounded-xl overflow-hidden w-full max-w-3xl mx-4 shadow-2xl border border-blue-300">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center">
            &times;
        </button>

        <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4">
            <h2 class="text-xl font-semibold text-center text-white">
                Click on the Image to Select a Point
            </h2>
        </div>

        <div class="p-4">
            <div class="relative overflow-auto border-2 border-dashed border-gray-300 rounded-lg bg-gray-50" style="max-height: 60vh;">
                <img id="modalImage" src="" alt="Plan" 
                     class="mx-auto cursor-crosshair w-full h-auto object-contain"
                     onclick="selectPoint(event)">
                <div id="pointMarker" class="absolute w-5 h-5 bg-red-600 rounded-full border-2 border-white hidden shadow-lg" style="transform: translate(-50%, -50%);"></div>
            </div>

            <form id="pointForm" method="POST" action="{{ route('branches.store.point') }}" class="mt-4 space-y-4">
                @csrf
                <input type="hidden" name="branche_id" value="{{ $branche->id }}">
                <input type="hidden" name="image_index" id="imageIndex">
                <input type="hidden" name="x" id="pointX">
                <input type="hidden" name="y" id="pointY">
                
                <div class="bg-blue-50 p-4 rounded-lg">
                    <label class="block text-blue-800 font-medium mb-3">Link Type</label>
                    <div class="flex gap-6">
                        <label class="inline-flex items-center">
                            <input type="radio" name="link_type" value="room" checked class="form-radio h-5 w-5 text-blue-600">
                            <span class="ml-2 text-gray-700">Room</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="link_type" value="corridor" class="form-radio h-5 w-5 text-blue-600">
                            <span class="ml-2 text-gray-700">Corridor</span>
                        </label>
                    </div>
                </div>
                
                <div class="mb-4" id="roomSelectContainer">
                    <label for="room_id" class="block text-gray-700 font-medium mb-2">Select Room</label>
                    <select name="room_id" id="room_id" class="form-select block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 border">
                        @foreach($branche->site->locations->flatMap->rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->code }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4 hidden" id="corridorSelectContainer">
                    <label for="corridor_id" class="block text-gray-700 font-medium mb-2">Select Corridor</label>
                    <select name="corridor_id" id="corridor_id" class="form-select block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 border">
                        @foreach($branche->site->locations->flatMap->corridors as $corridor)
                            <option value="{{ $corridor->id }}">{{ $corridor->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex justify-end">
                    <button type="button" onclick="closeImageModal()" class="mr-3 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition shadow">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition shadow flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Save Link
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
    let selectedX = 0;
    let selectedY = 0;

    function openImageModal(imageUrl, imageIndex) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const pointMarker = document.getElementById('pointMarker');
        const imageIndexInput = document.getElementById('imageIndex');

        modal.classList.remove('hidden');
        modalImage.src = imageUrl;
        imageIndexInput.value = imageIndex;
        pointMarker.classList.add('hidden');
        
        // Reset form selections
        document.querySelector('input[name="link_type"][value="room"]').checked = true;
        document.getElementById('roomSelectContainer').classList.remove('hidden');
        document.getElementById('corridorSelectContainer').classList.add('hidden');
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
    }

    function selectPoint(event) {
        const image = event.target;
        const marker = document.getElementById('pointMarker');

        const rect = image.getBoundingClientRect();
        const x = ((event.clientX - rect.left) / rect.width) * 100;
        const y = ((event.clientY - rect.top) / rect.height) * 100;

        selectedX = x.toFixed(2);
        selectedY = y.toFixed(2);

        // Position marker
        marker.style.left = `${selectedX}%`;
        marker.style.top = `${selectedY}%`;
        marker.classList.remove('hidden');

        // Update hidden inputs
        document.getElementById('pointX').value = selectedX;
        document.getElementById('pointY').value = selectedY;
    }

    // Toggle room/corridor dropdowns
    document.querySelectorAll('input[name="link_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const roomSelect = document.getElementById('roomSelectContainer');
            const corridorSelect = document.getElementById('corridorSelectContainer');

            if (this.value === 'room') {
                roomSelect.classList.remove('hidden');
                corridorSelect.classList.add('hidden');
            } else {
                corridorSelect.classList.remove('hidden');
                roomSelect.classList.add('hidden');
            }
        });
    });
</script>

@endsection