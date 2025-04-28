@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>
    
    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-6 pb-16">
        <!-- Dashboard content with glassmorphism effect -->
        <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-8 max-w-2xl mx-auto mt-8 transition-all duration-500 transform hover:scale-[1.01]">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-blue-900">Add New Room</h1>
                <p class="text-gray-600 mt-1">Location: {{ $location->name }}</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('superadmin.locations.storeRoom', $location) }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Room Name</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Room Code</label>
                    <input type="text" name="code" id="code" required value="{{ old('code') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                    <select name="type" id="type" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Type</option>
                        <option value="Office" @selected(old('type') == 'Office')>Bureau</option>
                        <option value="Storage" @selected(old('type') == 'Storage')>Salle réunion</option>
                        <option value="Meeting" @selected(old('type') == 'Meeting')>Salle Réseau</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-4 pt-4">
                    <a href="{{ route('superadmin.locations.rooms', $location) }}"
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg shadow hover:shadow-md transition">
                        Save Room
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection