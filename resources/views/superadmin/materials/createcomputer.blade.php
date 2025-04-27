@extends('layouts.app') {{-- Or your dashboard layout --}}

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded shadow-md mt-6">
    <h2 class="text-2xl font-bold mb-6 text-blue-800">Ajouter un ordinateur</h2>

    <form action="{{ route('superadmin.computers.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="hostname" class="block font-semibold text-gray-700">Hostname</label>
            <input type="text" name="hostname" id="hostname" value="{{ old('hostname') }}"
                   class="w-full p-2 border rounded" required>
            @error('hostname')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="serial_number" class="block font-semibold text-gray-700">Serial Number</label>
            <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number') }}"
                   class="w-full p-2 border rounded" required>
            @error('serial_number')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="brand" class="block font-semibold text-gray-700">Brand</label>
            <input type="text" name="brand" id="brand" value="{{ old('brand') }}"
                   class="w-full p-2 border rounded" required>
            @error('brand')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="model" class="block font-semibold text-gray-700">Model</label>
            <input type="text" name="model" id="model" value="{{ old('model') }}"
                   class="w-full p-2 border rounded" required>
            @error('model')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="operating_system" class="block font-semibold text-gray-700">Operating System</label>
            <input type="text" name="operating_system" id="operating_system" value="{{ old('operating_system') }}"
                   class="w-full p-2 border rounded" required>
            @error('operating_system')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="room_id" class="block font-semibold text-gray-700">Room</label>
            <select name="room_id" id="room_id" class="w-full p-2 border rounded" required>
                <option value="">-- Select a Room --</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                        {{ $room->name }} ({{ $room->location->site ?? 'No Site' }})
                    </option>
                @endforeach
            </select>
            @error('room_id')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-6">
            <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">
                Enregistrer
            </button>
            <a href="{{ route('superadmin.materials.computers') }}"
               class="ml-4 text-gray-600 hover:text-gray-800">Annuler</a>
        </div>
    </form>
</div>
@endsection
