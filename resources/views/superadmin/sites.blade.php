@extends('layouts.app')

@section('content')

<!-- Blurred Background -->
<div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

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
                   <img src="{{ Storage::url($image) }}" 
     alt="Plan Image {{ $index + 1 }}"
     class="w-full h-auto rounded-lg">
                    <h2 class="text-lg font-bold text-blue-800 mt-4 text-center">Plan – {{ $index + 1 }}</h2>
                    <!-- Add Link Button -->

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
    <div class="relative bg-white rounded-lg overflow-hidden max-w-3xl w-full p-4">

        <!-- Close Button -->
        <button onclick="closeImageModal()" class="absolute top-2 right-2 text-gray-700 hover:text-black text-2xl font-bold">
            ✕
        </button>

        <h2 class="text-xl font-semibold text-center text-blue-900 mb-4">
            Click on the Image to Select a Point
        </h2>

        <!-- Image Container -->
        <!-- Image Container -->
<div class="relative overflow-auto border border-gray-300 max-h-[50vh]">
    <img id="modalImage" src="" alt="Plan" 
         class="mx-auto cursor-crosshair max-h-[60vh] object-contain"
         onclick="selectPoint(event)">
    <div id="pointMarker" class="absolute w-4 h-4 bg-red-600 rounded-full hidden" style="transform: translate(-50%, -50%);"></div>
</div>


        <!-- Form for Saving Link -->
        <form action="{{ route('superadmin.sites.store') }}" method="POST" class="mt-4 text-center">
            @csrf
            <input type="hidden" name="x" id="pointX">
            <input type="hidden" name="y" id="pointY">
            <input type="text" name="label" placeholder="Label (Room or Corridor)" class="border p-2 rounded w-1/2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition ml-2">
                Save Link
            </button>
        </form>
    </div>
</div>

<!-- JS for Modal and Point Selection -->
<script>
    function openImageModal(imageUrl) {
        const modal = document.getElementById('imageModal');
        const img = document.getElementById('modalImage');
        const pointMarker = document.getElementById('pointMarker');

        img.src = imageUrl;
        pointMarker.style.display = 'none';
        modal.classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }

    function selectPoint(event) {
        const img = event.target;
        const marker = document.getElementById('pointMarker');

        const rect = img.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;

        // Position the marker (centered)
        marker.style.left = `${x}px`;
        marker.style.top = `${y}px`;
        marker.style.display = 'block';

        // Store coordinates
        document.getElementById('pointX').value = x;
        document.getElementById('pointY').value = y;
    }
</script>
