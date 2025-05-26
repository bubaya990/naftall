@extends('layouts.app')

@section('content')
<div class="relative">
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.webp'); filter: blur(6px);"></div>

    <div class="relative z-10 min-h-screen p-6 pb-16">
      <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-8 max-w-7xl mx-auto mt-8 transition-all duration-500 transform hover:scale-[1.01]">
    <!-- Button aligned right -->
    <div class="mb-4 flex justify-end">
        <a href="{{ route('superadmin.dashboard') }}" 
           class="text-yellow-700 hover:text-yellow-900 transition-colors duration-200 bg-yellow-100 hover:bg-yellow-200 px-4 py-2 rounded-lg font-medium border border-yellow-200">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>
    </div>

    <!-- Centered title -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-blue-900 text-center">
            Structure Commerciale
        </h1>
    </div>



            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Main Cards Section -->
                <div class="col-span-1 md:col-span-2 lg:col-span-3">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($commercialBranches && $commercialBranches->isNotEmpty())
                            @foreach($commercialBranches as $commercialBranche)
                                @if($commercialBranche->children)
                                    @foreach($commercialBranche->children as $child)
                                        @if($child->name === 'Agence')
                                            <!-- Agence Card -->
                                            <a href="{{ route('superadmin.sites.branche', ['site' => $commercialBranche->site_id, 'brancheType' => 'Agence']) }}">
                                                <div class="bg-white rounded-xl shadow-lg overflow-hidden h-full">
                                                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                                                        <div class="flex items-center justify-center mb-2">
                                                            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                                                                <i class="fas fa-building text-white text-2xl"></i>
                                                            </div>
                                                        </div>
                                                        <h2 class="text-xl font-bold text-white text-center">Agence</h2>
                                                    </div>
                                                    <div class="p-4">
                                                        <div class="flex flex-wrap gap-2">
                                                            @if($child->children)
                                                                @foreach($child->children as $subChild)
                                                                    @if($subChild->name !== 'GD')
                                                                        <a href="{{ route('superadmin.sites.branche.detail', ['site' => $commercialBranche->site_id, 'brancheType' => 'Agence', 'branche' => $subChild->id]) }}" class="flex-1 min-w-[120px]">
                                                                            <div class="bg-blue-50 text-blue-600 rounded-lg p-3 text-center">
                                                                                {{ $subChild->name }}
                                                                            </div>
                                                                        </a>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @elseif($child->name === 'LP')
                                            <!-- LP Card -->
                                            <a href="{{ route('superadmin.sites.branche', ['site' => $commercialBranche->site_id, 'brancheType' => 'LP']) }}">
                                                <div class="bg-white rounded-xl shadow-lg overflow-hidden h-full">
                                                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-4">
                                                        <div class="flex items-center justify-center mb-2">
                                                            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                                                                <i class="fas fa-gas-pump text-white text-2xl"></i>
                                                            </div>
                                                        </div>
                                                        <h2 class="text-xl font-bold text-white text-center">LP</h2>
                                                    </div>
                                                    <div class="p-4">
                                                        <div class="flex flex-wrap gap-2">
                                                            @if($child->children)
                                                                @foreach($child->children as $subChild)
                                                                    <a href="{{ route('superadmin.sites.branche.detail', ['site' => $commercialBranche->site_id, 'brancheType' => 'LP', 'branche' => $subChild->id]) }}" class="flex-1 min-w-[120px]">
                                                                        <div class="bg-green-50 text-green-600 rounded-lg p-3 text-center">
                                                                            {{ $subChild->name }}
                                                                        </div>
                                                                    </a>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @elseif($child->name === 'CDD')
                                            <!-- CDD Card -->
                                            <a href="{{ route('superadmin.sites.branche', ['site' => $commercialBranche->site_id, 'brancheType' => 'CDD']) }}">
                                                <div class="bg-white rounded-xl shadow-lg overflow-hidden h-full">
                                                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-4">
                                                        <div class="flex items-center justify-center mb-2">
                                                            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                                                                <i class="fas fa-store text-white text-2xl"></i>
                                                            </div>
                                                        </div>
                                                        <h2 class="text-xl font-bold text-white text-center">CDD</h2>
                                                    </div>
                                                    <div class="p-4">
                                                        <div class="flex flex-wrap gap-2">
                                                            @if($child->children)
                                                                @foreach($child->children as $subChild)
                                                                    <a href="{{ route('superadmin.sites.branche.detail', ['site' => $commercialBranche->site_id, 'brancheType' => 'CDD', 'branche' => $subChild->id]) }}" class="flex-1 min-w-[120px]">
                                                                        <div class="bg-yellow-50 text-yellow-600 rounded-lg p-3 text-center">
                                                                            {{ $subChild->name }}
                                                                        </div>
                                                                    </a>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @else
                            <div class="col-span-3 text-center py-8">
                                <p class="text-gray-500">Aucune structure commerciale trouvÃ©e.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- GD Card with Dropdown -->
                @if($commercialBranches && $commercialBranches->isNotEmpty())
                    @foreach($commercialBranches as $commercialBranche)
                        @if($commercialBranche->children)
                            @foreach($commercialBranche->children as $child)
                                @if($child->name === 'Agence' && $child->children)
                                    @foreach($child->children as $subChild)
                                        @if($subChild->name === 'GD' && $subChild->children && $subChild->children->isNotEmpty())
                                            <div x-data="{ isOpen: false }" class="bg-white rounded-xl shadow-lg overflow-hidden">
                                                <div @click="isOpen = !isOpen" class="bg-gradient-to-r from-purple-500 to-purple-600 p-4 cursor-pointer">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center justify-center mb-2">
                                                            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                                                                <i class="fas fa-warehouse text-white text-2xl"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h2 class="text-xl font-bold text-white text-center">GD</h2>
                                                        </div>
                                                        <svg :class="{ 'rotate-180': isOpen }" class="w-5 h-5 text-white transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div x-show="isOpen" x-transition class="p-4 bg-purple-50">
                                                    <div class="flex flex-col gap-2">
                                                        @foreach($subChild->children as $station)
                                                            <a href="{{ route('superadmin.sites.branche.detail', ['site' => $commercialBranche->site_id, 'brancheType' => 'GD', 'branche' => $station->id]) }}" class="block">
                                                                <div class="bg-white text-purple-600 rounded-lg p-2 text-center text-sm shadow-sm">
                                                                    {{ $station->name }}
                                                                </div>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection