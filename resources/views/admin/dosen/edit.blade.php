@extends('layouts.admin.app')

@section('title', 'Edit Dosen')

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
                    <span class="ml-1 text-gray-500 md:ml-2">Edit Dosen</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="mb-6 text-xl font-semibold">Edit Data Dosen</h2>

        @if(session('success'))
            <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.dosen.update', $dosen->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nama_dosen" class="block mb-2 text-sm font-medium text-gray-700">Nama Dosen</label>
                <input type="text" name="nama_dosen" id="nama_dosen"
                       value="{{ old('nama_dosen', $dosen->nama_dosen) }}"
                       class="w-full h-10 px-4 border border-gray-300 rounded-md focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="nip" class="block mb-2 text-sm font-medium text-gray-700">NIP</label>
                <input type="text" name="nip" id="nip"
                       value="{{ old('nip', $dosen->nip) }}"
                       class="w-full h-10 px-4 border border-gray-300 rounded-md focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email"
                       value="{{ old('email', $dosen->email) }}"
                       class="w-full h-10 px-4 border border-gray-300 rounded-md focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="no_hp" class="block mb-2 text-sm font-medium text-gray-700">No HP</label>
                <input type="text" name="no_hp" id="no_hp"
                       value="{{ old('no_hp', $dosen->no_hp) }}"
                       class="w-full h-10 px-4 border border-gray-300 rounded-md focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="prodi" class="block mb-2 text-sm font-medium text-gray-700">Prodi</label>
                <input type="text" name="prodi" id="prodi"
                       value="{{ old('prodi', $dosen->prodi) }}"
                       class="w-full h-10 px-4 border border-gray-300 rounded-md focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" name="password" id="password"
                       class="w-full h-10 px-4 border border-gray-300 rounded-md focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="profile" class="block mb-2 text-sm font-medium text-gray-700">Foto Profil</label>
                @if($dosen->profile)
                    <div class="mb-2">
                        <img src="{{ asset('profiles/' . $dosen->profile) }}"
                             alt="Profile"
                             class="w-32 h-32 rounded-full">
                    </div>
                @endif
                <input type="file" name="profile" id="profile"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-blue-500">
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('admin.dosen.index') }}"
                   class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
