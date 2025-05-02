@extends('layouts.app')

@section('content')
<div class="relative">
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

    <div class="relative z-10 min-h-screen p-6 pb-16">
        <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-8 max-w-7xl mx-auto mt-8">
            <h1 class="text-3xl font-bold text-blue-900 mb-8">
                Agence - {{ $site->name }}
            </h1>
            
            <div class="flex justify-center">
                <img src="/image/PLAN siege (RDC).jpg" alt="Agence RDC" class="rounded-lg shadow-lg max-w-xs">
            </div>
        </div>
    </div>
</div>
@endsection