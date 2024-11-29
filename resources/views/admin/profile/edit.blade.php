@extends('layouts.admin.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container px-4 py-8 mx-auto">
    <!-- Alert Component -->
    <div id="alert" class="fixed top-4 right-4 px-4 py-3 bg-green-100 rounded-lg border-l-4 border-green-500 shadow-lg transition-transform duration-300 transform {{ session('success') ? '' : 'hidden' }}">
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <p class="font-medium text-green-700">{{ session('success') ?? 'Changes saved successfully!' }}</p>
        </div>
    </div>

    <div class="mx-auto max-w-3xl bg-white rounded-xl border border-gray-100 shadow-lg">
        <!-- Profile Header -->
        <div class="p-6 bg-gradient-to-bl from-blue-400 to-blue-500 rounded-t-xl border-b">
            <h2 class="text-2xl font-bold text-white">Profile Settings</h2>
        </div>

        <!-- Profile Photo Section -->
        <div class="p-8 bg-gray-50 border-b">
            <div class="flex justify-center items-center space-x-6">
                <div class="flex relative flex-col items-center">
                    @if($admin->profile)
                        <img src="{{ Storage::url($admin->profile) }}"
                             alt="Profile Photo"
                             class="object-cover w-40 h-40 rounded-full border-4 border-white shadow-lg">
                    @else
                        <div class="flex justify-center items-center w-40 h-40 bg-gradient-to-r from-blue-400 to-blue-500 rounded-full border-4 border-white shadow-lg">
                            <span class="text-5xl font-semibold text-white">{{ substr($admin->name, 0, 1) }}</span>
                        </div>
                    @endif

                    <div class="flex mt-6 space-x-3">
                        <form action="{{ route('admin.profile.photo.update') }}" method="POST" enctype="multipart/form-data" id="photoForm" class="inline">
                            @csrf
                            <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*" onchange="this.form.submit()">
                            <label for="profile_photo" class="inline-block px-4 py-2 text-sm font-medium text-white bg-blue-400 rounded-lg shadow-md transition duration-200 cursor-pointer hover:bg-blue-500">
                                Change Photo
                            </label>
                        </form>

                        @if($admin->profile)
                            <form action="{{ route('admin.profile.photo.destroy') }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-block px-4 py-2 text-sm font-medium text-red-600 bg-white rounded-lg border border-red-200 shadow-md transition duration-200 hover:bg-red-50">
                                    Remove Photo
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Information Form -->
        <form action="{{ route('admin.profile.update') }}" method="POST" class="p-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700">Name</label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name', $admin->name) }}"
                           class="block px-4 py-3 w-full rounded-lg border border-gray-300 shadow-sm transition duration-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email', $admin->email) }}"
                           class="block px-4 py-3 w-full rounded-lg border border-gray-300 shadow-sm transition duration-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                </div>
            </div>

            <!-- Password Section -->
            <div class="pt-8 mt-8 border-t border-gray-200">
                <h3 class="mb-6 text-xl font-semibold text-gray-900">Update Password</h3>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label for="current_password" class="block text-sm font-semibold text-gray-700">Current Password</label>
                        <input type="password" name="current_password" id="current_password"
                               class="block px-4 py-3 w-full rounded-lg border border-gray-300 shadow-sm transition duration-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">New Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                   class="block px-4 py-3 w-full rounded-lg border border-gray-300 shadow-sm transition duration-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                                   onkeyup="checkPasswordMatch()">
                            <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5 text-gray-500 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="hidden w-5 h-5 text-gray-500 eye-closed" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Confirm New Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="block px-4 py-3 w-full rounded-lg border border-gray-300 shadow-sm transition duration-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                                   onkeyup="checkPasswordMatch()">
                            <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5 text-gray-500 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="hidden w-5 h-5 text-gray-500 eye-closed" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <p id="password_message" class="hidden mt-2 text-sm font-medium"></p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-8">
                <button type="submit" class="px-6 py-3 text-sm font-medium text-white bg-blue-400 rounded-lg shadow-md transition duration-200 hover:bg-blue-500 focus:ring-4 focus:ring-blue-400 focus:ring-opacity-50">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function checkPasswordMatch() {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const message = document.getElementById('password_message');

        if (password.value || confirmPassword.value) {
            message.classList.remove('hidden');
            if (password.value !== confirmPassword.value) {
                message.classList.remove('text-green-600');
                message.classList.add('text-red-500');
                message.textContent = '❌ Passwords do not match!';
            } else {
                message.classList.remove('text-red-500');
                message.classList.add('text-green-600');
                message.textContent = '✓ Passwords match!';
            }
        } else {
            message.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.getElementById('alert');

        if (!alert.classList.contains('hidden')) {
            setTimeout(() => {
                alert.classList.add('translate-y-[-100%]');
                setTimeout(() => {
                    alert.classList.add('hidden');
                    alert.classList.remove('translate-y-[-100%]');
                }, 300);
            }, 5000);
        }
    });

    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const button = input.nextElementSibling;
        const eyeOpen = button.querySelector('.eye-open');
        const eyeClosed = button.querySelector('.eye-closed');

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
</script>
@endsection
