@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6 text-blue-900">
        {{ $site->name }} – {{ ucfirst($branche->name) }}
    </h1>

    {{-- === COMMERCIAL BRANCH HANDLING === --}}
    @if(strtolower($branche->name) === 'commercial')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($branche->children as $child)
                @if(strtolower($child->name) === 'agence')
                    <div class="bg-white/80 rounded-xl shadow-lg p-6 text-center hover:scale-105 transition-transform">
                        <img src="{{ asset('image/branches/' . $child->image) }}"
                             alt="{{ $child->name }}"
                             class="w-full h-48 object-cover rounded-lg mb-4">
                        <h2 class="text-xl font-bold text-blue-800">{{ $child->name }}</h2>
                        <p class="text-gray-600 mt-2">Child of Commercial</p>
                    </div>
                @endif
            @empty
                <div class="text-center col-span-full py-12">
                    <p class="text-xl text-gray-600">No child branches available</p>
                </div>
            @endforelse
        </div>

    {{-- === SPECIAL CASE: AGENCE (CHILD OF COMMERCIAL IN SIEGE) === --}}
    @elseif(strtolower($branche->name) === 'agence' && strtolower($site->name) === 'siege')
        <div class="grid grid-cols-1 gap-6">
            <div class="bg-white/80 rounded-xl shadow-lg p-6 text-center hover:scale-105 transition-transform">
                <img src="{{ asset('image/PLAN siege (RDC).jpg') }}"
                     alt="PLAN siege (RDC)"
                     class="w-full h-auto rounded-lg mb-4">
                <h2 class="text-lg font-bold text-blue-800">Plan – RDC (Agence)</h2>
            </div>
        </div>

    {{-- === CARBURANT IN SIEGE (ALL THREE FLOORS) === --}}
    @elseif(strtolower($branche->name) === 'carburant' && strtolower($site->name) === 'siege')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(['etage 1', 'etage 2', 'etage 3'] as $etage)
                <div class="bg-white/80 rounded-xl shadow-lg p-6 text-center hover:scale-105 transition-transform">
                    <img src="{{ asset("image/PLAN siege ($etage).jpg") }}"
                         alt="PLAN siege {{ $etage }}"
                         class="w-full h-auto rounded-lg mb-4">
                    <h2 class="text-lg font-bold text-blue-800">Plan – {{ ucfirst($etage) }}</h2>
                </div>
            @endforeach
        </div>

    {{-- === CARBURANT IN OTHER SITES === --}}
    @elseif(strtolower($branche->name) === 'carburant')
        <div class="grid grid-cols-1 gap-6">
            {{-- You can add specific content for 'carburant' in non-'siege' sites here --}}
        </div>

    {{-- === FALLBACK CASE === --}}
    @else
        <div class="text-center text-red-600 text-xl font-semibold">
            Nothing to display for this branch.
        </div>
    @endif
</div>
@endsection