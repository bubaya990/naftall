@extends('layouts.app')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4 text-blue-800">Create New Hotspot Material</h1>

    <form action="{{ route('superadmin.materials.storeHotspot') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
            <input type="text" name="password" id="password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label for="brand" class="block text-sm font-semibold text-gray-700">Brand</label>
            <input type="text" name="brand" id="brand" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label for="model" class="block text-sm font-semibold text-gray-700">Model</label>
            <input type="text" name="model" id="model" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label for="state" class="block text-sm font-semibold text-gray-700">State</label>
            <input type="text" name="state" id="state" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label for="room" class="block text-sm font-semibold text-gray-700">Room</label>
            <select name="room_id" id="room" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500" required>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="corridor" class="block text-sm font-semibold text-gray-700">Corridor</label>
            <select name="corridor_id" id="corridor" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500" required>
                @foreach($corridors as $corridor)
                    <option value="{{ $corridor->id }}">{{ $corridor->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Create Hotspot</button>
        </div>
    </form>
</div>
@endsection
