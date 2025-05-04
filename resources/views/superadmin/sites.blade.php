@extends('layouts.app')

@section('content')
<div class="relative">
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/naftalBg.jpeg'); filter: blur(6px);"></div>

    <div class="relative z-10 min-h-screen p-6 pb-16">
        <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-8 max-w-7xl mx-auto mt-8">
            <h1 class="text-3xl font-bold text-blue-900 mb-8">
                @if($brancheType)
                    {{ ucfirst($brancheType) }} - {{ $site->name }}
                @else
                    {{ $site->name }}
                @endif
            </h1>

            {{-- Carburant Branch --}}
            @if($brancheType === 'carburant')
                @if(isset($showFloorPlans) && $showFloorPlans)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach(['etage 1', 'etage 2', 'etage 3'] as $floor)
                            <div class="text-center">
                                <img src="/image/PLAN siege ({{ $floor }}).jpg" 
                                     alt="Floor {{ $floor }}"
                                     class="rounded-lg shadow-lg mx-auto w-full max-w-xs hover:scale-105 transition-transform">
                                <p class="text-center mt-2 font-medium">Floor {{ $floor }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-xl text-gray-600">No plans available for this site</p>
                    </div>
                @endif

            {{-- Agence Branch --}}
            @elseif($brancheType === 'agence')
                @if(isset($showAgencePlan) && $showAgencePlan)
                    <div class="flex justify-center">
                        <img src="/image/PLAN siege (RDC).jpg" 
                             alt="Agence RDC"
                             class="rounded-lg shadow-lg max-w-xs hover:scale-105 transition-transform">
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-xl text-gray-600">No plan available for this agence</p>
                    </div>
                @endif

            {{-- Default Site View --}}
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="{{ route('sites.branche', ['site' => $site->id, 'brancheType' => 'carburant']) }}" 
                       class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-xl shadow-lg p-8 flex flex-col items-center justify-center transition-all hover:scale-105">
                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-gas-pump text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold">Carburant</h2>
                    </a>

                    <a href="{{ route('sites.branche', ['site' => $site->id, 'brancheType' => 'commercial']) }}" 
                       class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-lg p-8 flex flex-col items-center justify-center transition-all hover:scale-105">
                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-store-alt text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold">Commercial</h2>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection