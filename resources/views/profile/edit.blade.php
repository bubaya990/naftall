@extends('layouts.app')
@section('content')

    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
            {{ __('Manage Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Profile Information -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Update Profile Information</h3>
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Change Password</h3>
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6">
                <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">Delete Account</h3>
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>

@endsection