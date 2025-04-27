@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>
    
    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-6 pb-16">
        <!-- Dashboard content with glassmorphism effect -->
        <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-8 max-w-7xl mx-auto mt-8 transition-all duration-500 transform hover:scale-[1.01]">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div class="animate-slideInLeft">
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-900">Rooms Management</h1>
                    <p class="text-gray-600 mt-1">Location: {{ $rooms->first()->location->name ?? 'N/A' }}</p>
                </div>
                
                <!-- Create button with animation -->
                <div class="animate-slideInRight">
                    <a href="{{ route('superadmin.locations.addroom', ['location' => $locationId]) }}" 
                       class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 inline-flex items-center">
                        <i class="fas fa-plus-circle mr-2"></i> Add New Room
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg animate-fadeIn">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg animate-fadeIn">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Rooms Table -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg overflow-hidden animate-fadeIn">
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
                                    <form action="{{ route('superadmin.locations.update-type', ['location' => $locationId, 'room' => $room->id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <select name="type" onchange="this.form.submit()" 
                                                class="border rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white/80">
                                         @foreach($rooms as $room)   <option value=" {{ $room->type  }}">{{ $room->type  }}</option>@endforeach 
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('superadmin.locations.destroyRoom', ['location' => $locationId, 'room' => $room->id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Are you sure you want to delete this room?')" 
                                                class="text-red-600 hover:text-red-800 transition-colors duration-200">
                                            <i class="fas fa-trash-alt mr-1"></i> Delete
                                        </button>
                                    </form>
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

<style>
    .animate-slideInLeft {
        animation: slideInLeft 0.5s ease-out;
    }
    .animate-slideInRight {
        animation: slideInRight 0.5s ease-out;
    }
    .animate-fadeIn {
        animation: fadeIn 0.6s ease-in;
    }
    @keyframes slideInLeft {
        from { transform: translateX(-20px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideInRight {
        from { transform: translateX(20px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@endsection