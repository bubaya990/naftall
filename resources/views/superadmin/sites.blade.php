@extends('layouts.app')

@section('content')

<!-- Blurred Background -->
<div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.webp'); filter: blur(6px);"></div>

<div class="p-6 relative z-10">
    <h1 class="text-2xl font-bold mb-6 text-blue-900">
        {{ $site->name }} – {{ ucfirst($branche->name) }}
    </h1>

    <!-- Upload Plans Form -->
    <div class="mb-6">
        @if(session('success'))
            <div class="text-green-600 mb-4 font-semibold">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="text-red-600 mb-4 font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('branches.upload.plans', $branche->id) }}" method="POST" enctype="multipart/form-data" class="bg-white/80 p-4 rounded-lg shadow-md">
            @csrf
            <label class="block text-blue-800 font-semibold mb-2">Upload Plan Images</label>
            <input type="file" name="plans[]" multiple class="mb-4 block w-full text-sm text-gray-700" required>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Upload</button>
        </form>
    </div>

    <!-- Display Plans Dynamically -->
    @if (!empty($branche->plan_images))
        <div class="flex flex-col items-center gap-6">
@foreach ($branche->plan_images as $index => $image)
    <div class="bg-white/80 rounded-xl shadow-lg p-6 w-full max-w-3xl relative">
        <div class="relative">
            <img src="{{ Storage::url($image) }}" 
                 alt="Plan Image {{ $index + 1 }}"
                 class="w-full h-auto rounded-lg">
            
            <!-- Display links for this image -->
            @foreach ($branche->plan_links ?? [] as $link)
                @if ($link['image_index'] == $index)
                    <a href="{{ $link['url'] }}" 
                       class="absolute w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs"
                       style="left: {{ $link['x'] }}%; top: {{ $link['y'] }}%; transform: translate(-50%, -50%);">
                        {{ $loop->iteration }}
                    </a>
                @endif
            @endforeach
        </div>
        
        <h2 class="text-lg font-bold text-blue-800 mt-4 text-center">Plan – {{ $index + 1 }}</h2>
        
        <button 
            onclick="openImageModal('{{ Storage::url($image) }}', {{ $index }})"
            class="mt-4 bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
            Select Action Point
        </button>
        
        <!-- Delete Button -->
        <form action="{{ route('branches.delete.plan', ['branche' => $branche->id, 'imageIndex' => $index]) }}" method="POST" class="absolute top-4 right-4">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition" onclick="return confirm('Are you sure you want to delete this plan?')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </form>
    </div>
@endforeach
        </div>
    @else
        <div class="text-center text-gray-600 text-xl mt-10">No plan images uploaded yet.</div>
    @endif
</div>

@endsection



<!-- Modal for Image Selection -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50 hidden">
    <div class="relative bg-white rounded-lg overflow-hidden max-w-2xl w-full p-4">
        <button onclick="closeImageModal()" class="absolute top-2 right-2 text-gray-700 hover:text-black text-2xl font-bold">
            ✕
        </button>

        <h2 class="text-xl font-semibold text-center text-blue-900 mb-4">
            Click on the Image to Select a Point
        </h2>

        <div class="relative overflow-auto border border-gray-300 max-h-[50vh]">
            <img id="modalImage" src="" alt="Plan" 
                 class="mx-auto cursor-crosshair max-h-[60vh] object-contain"
                 onclick="selectPoint(event)">
            <div id="pointMarker" class="absolute w-4 h-4 bg-red-600 rounded-full hidden" style="transform: translate(-50%, -50%);"></div>
        </div>

        <form id="pointForm" method="POST" action="{{ route('branches.store.point') }}" class="mt-4">
            @csrf
            <input type="hidden" name="branche_id" value="{{ $branche->id }}">
            <input type="hidden" name="image_index" id="imageIndex">
            <input type="hidden" name="x" id="pointX">
            <input type="hidden" name="y" id="pointY">
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Link Type</label>
                <div class="flex gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="link_type" value="room" checked class="form-radio text-blue-600">
                        <span class="ml-2">Room</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="link_type" value="corridor" class="form-radio text-blue-600">
                        <span class="ml-2">Corridor</span>
                    </label>
                </div>
            </div>
            
            <div class="mb-4" id="roomSelectContainer">
                <label for="room_id" class="block text-gray-700 mb-2">Select Room</label>
                <select name="room_id" id="room_id" class="form-select block w-full border-gray-300 rounded-md">
                    @foreach($branche->site->locations->flatMap->rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->code }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4 hidden" id="corridorSelectContainer">
                <label for="corridor_id" class="block text-gray-700 mb-2">Select Corridor</label>
                <select name="corridor_id" id="corridor_id" class="form-select block w-full border-gray-300 rounded-md">
                    @foreach($branche->site->locations->flatMap->corridors as $corridor)
                        <option value="{{ $corridor->id }}">{{ $corridor->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Save Link
            </button>
        </form>
    </div>
</div>

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
