@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

    <!-- Main content container -->
    <div class="relative z-10 min-h-screen p-4 pb-12">
        <!-- Dashboard content with glassmorphism effect -->
        <div class="bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl p-6 max-w-4xl mx-auto mt-6 transition-all duration-500 transform hover:scale-[1.01]">
            <!-- Header -->
            <div class="mb-6 text-center animate-slideInLeft">
                <h1 class="text-xl md:text-2xl font-bold text-blue-900">Manage Profile</h1>
                <p class="text-blue-900 mt-1">Update your account information and settings</p>
            </div>

            <!-- Profile Information -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 backdrop-blur-sm rounded-xl shadow-lg p-6 mb-6 border-l-4 border-blue-500 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl">
                <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center">
                    <i class="fas fa-user-circle mr-2"></i> Profile Information
                </h3>
                <div class="max-w-2xl mx-auto">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 backdrop-blur-sm rounded-xl shadow-lg p-6 mb-6 border-l-4 border-green-500 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl">
                <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center">
                    <i class="fas fa-key mr-2"></i> Change Password
                </h3>
                <div class="max-w-2xl mx-auto">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="bg-gradient-to-br from-red-50 to-red-100 backdrop-blur-sm rounded-xl shadow-lg p-6 border-l-4 border-red-500 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl">
                <h3 class="text-lg font-bold text-red-700 mb-4 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Delete Account
                </h3>
                <div class="max-w-2xl mx-auto">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-slideInLeft {
        animation: slideInLeft 0.5s ease-out forwards;
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>
@endsection