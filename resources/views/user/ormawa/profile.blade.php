@extends('layouts.ormawa')
@section('title', 'Profil Ormawa')
@section('content')
    <div class="container mx-auto p-6 border-2 border-blue-500 rounded-md">
        <div class="bg-gray-200 p-6 rounded-md">
            <!-- Profile Picture Section -->
            <div class="flex items-center mb-6">
                <div class="w-24 h-24 bg-gray-300 rounded-full mr-4">
                    <img src="{{ $ormawa->profile_photo_url ?? 'path/to/default-image.jpg' }}" alt="Profile Photo" class="rounded-full w-full h-full object-cover">
                </div>
                <div>
                    <button class="bg-yellow-500 text-white px-4 py-2 rounded mr-2" onclick="document.getElementById('profilePhotoInput').click();">Change Picture</button>
                    <button class="bg-black text-white px-4 py-2 rounded" onclick="removePhoto()">Delete Picture</button>
                </div>
            </div>

            <!-- User Details Form -->
            <form id="profilePhotoForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="file" id="profilePhotoInput" name="profile_photo" class="hidden" onchange="submitProfilePhotoForm();">
                
                <div class="mb-4">
                    <label for="name" class="block">Nama:</label>
                    <input type="text" id="name" name="name" value="{{ $ormawa->nama_mahasiswa ?? '' }}" class="border rounded p-2 w-full">
                </div>
                <div class="mb-4">
                    <label for="phone" class="block">No HP:</label>
                    <input type="text" id="phone" name="phone" value="{{ $ormawa->no_hp ?? '' }}" class="border rounded p-2 w-full">
                </div>
                <div class="mb-4">  
                    <label for="email" class="block">Email:</label>
                    <input type="email" id="email" name="email" value="{{ $ormawa->email ?? '' }}" class="border rounded p-2 w-full">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
            </form>

        </div>
    </div>

    <script>
        function removePhoto() {
            // Add logic to remove photo
        }

        function submitProfilePhotoForm() {
            document.getElementById('profilePhotoForm').submit();
        }
    </script>
@endsection
