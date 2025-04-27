@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">Add IP Phone</h2>

    <form action="{{ route('superadmin.ipphones.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium">MAC Address</label>
            <input type="text" name="mac_address" class="w-full p-2 border rounded" required>
        </div>

        <div>
            <label class="block font-medium">Brand</label>
            <input type="text" name="brand" class="w-full p-2 border rounded" required>
        </div>

        <div>
            <label class="block font-medium">Model</label>
            <input type="text" name="model" class="w-full p-2 border rounded" required>
        </div>

        <div>
            <label class="block font-medium">Location Type</label>
            <select name="location_type" class="w-full p-2 border rounded" required>
                <option value="">-- Choose Location Type --</option>
                <option value="room">Room</option>
                <option value="corridor">Corridor</option>
            </select>
        </div>

        <div>
            <label class="block font-medium">Select Location</label>
            <select name="location_id" class="w-full p-2 border rounded" required>
                <option value="">-- Choose Room or Corridor --</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">Room: {{ $room->name }}</option>
                @endforeach
                @foreach($corridors as $corridor)
                    <option value="{{ $corridor->id }}">Corridor: {{ $corridor->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Add IP Phone</button>
    </form>
</div>
@endsection
