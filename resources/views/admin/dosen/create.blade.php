@extends('layouts.admin.app')

@section('title', 'Tambah Dosen')

@section('content')
<div class="container px-4 py-8 mx-auto sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.adminDashboard') }}" class="inline-flex items-center text-gray-700 hover:text-blue-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('admin.dosen.index') }}" class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2">Dosen</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-gray-500 md:ml-2">Tambah Dosen</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Form -->
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="mb-6 text-xl font-semibold">Tambah Dosen Baru</h2>

        <!-- Alert Success -->
        @if(session('success'))
        <div id="alert-success" class="relative px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        <!-- Alert Error dari Exception -->
        @if($errors->has('exception'))
        <div id="alert-error" class="relative px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
            <span class="block sm:inline">{{ $errors->first('exception') }}</span>
        </div>
        @endif



        <form action="{{ route('admin.dosen.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="nama_dosen" class="block mb-2 text-sm font-medium text-gray-700">Nama Dosen</label>
                <input type="text" name="nama_dosen" id="nama_dosen" class="w-full h-10 px-4 transition-colors border border-gray-500 rounded-md focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                @error('nama_dosen')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nip" class="block mb-2 text-sm font-medium text-gray-700">NIP</label>
                <input type="text" name="nip" id="nip" class="w-full h-10 px-4 transition-colors border border-gray-500 rounded-md focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                @error('nip')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="w-full h-10 px-4 transition-colors border border-gray-500 rounded-md focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="no_hp" class="block mb-2 text-sm font-medium text-gray-700">No HP</label>
                <input type="text" name="no_hp" id="no_hp" class="w-full h-10 px-4 transition-colors border border-gray-500 rounded-md focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                @error('no_hp')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="prodi" class="block mb-2 text-sm font-medium text-gray-700">Prodi</label>
                <input type="text" name="prodi" id="prodi" class="w-full h-10 px-4 transition-colors border border-gray-500 rounded-md focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                @error('prodi')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" class="w-full h-10 px-4 transition-colors border border-gray-500 rounded-md focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required autocomplete="off">
                    <button type="button" class="absolute text-gray-500 transform -translate-y-1/2 right-2 top-1/2 hover:text-gray-700" onclick="togglePassword()">
                        <svg id="eye-icon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eye-off-icon" class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="profile" class="block mb-2 text-sm font-medium text-gray-700">Profile</label>
                <input type="file" name="profile" id="profile" class="w-full px-4 py-2 transition-colors border border-gray-500 rounded-md focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                @error('profile')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.dosen.index') }}" class="px-4 py-2 mr-2 text-white bg-gray-500 rounded-md hover:bg-gray-600">Batal</a>
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">Tambahkan</button>
            </div>
        </form>
    </div>
</div>
@endsection


@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function togglePassword() {
    const password = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');
    const eyeOffIcon = document.getElementById('eye-off-icon');

    if (password.type === 'password') {
        password.type = 'text';
        eyeIcon.classList.add('hidden');
        eyeOffIcon.classList.remove('hidden');
    } else {
        password.type = 'password';
        eyeIcon.classList.remove('hidden');
        eyeOffIcon.classList.add('hidden');
    }
}

// Auto hide alerts after 3 seconds
$(document).ready(function() {
    // Handle success alert
    if ($('#alert-success').length > 0) {
        setTimeout(function() {
            $("#alert-success").fadeOut('slow');
        }, 3000);
    }

    // Handle error alert
    if ($('#alert-error').length > 0) {
        setTimeout(function() {
            $("#alert-error").fadeOut('slow');
        }, 3000);
    }
});
</script>
@endpush
