@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Printer</h2>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('superadmin.materials.printers.store') }}" method="POST">
        @csrf

        <!-- Printer Name -->
        <div class="form-group">
            <label for="name">Printer Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Printer Brand -->
        <div class="form-group">
            <label for="brand">Brand</label>
            <input type="text" name="brand" id="brand" class="form-control" value="{{ old('brand') }}" required>
            @error('brand')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Printer Model -->
        <div class="form-group">
            <label for="model">Model</label>
            <input type="text" name="model" id="model" class="form-control" value="{{ old('model') }}" required>
            @error('model')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Room Select -->
        <div class="form-group">
            <label for="room_id">Room</label>
            <select name="room_id" id="room_id" class="form-control" required>
                <option value="">Select Room</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                        {{ $room->name }}
                    </option>
                @endforeach
            </select>
            @error('room_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Create Printer</button>
    </form>
</div>
@endsection
