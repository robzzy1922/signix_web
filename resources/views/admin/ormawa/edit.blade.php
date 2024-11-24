@extends('layouts.admin.app')

@section('title', 'Edit Ormawa')

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
                    <a href="{{ route('admin.ormawa.index') }}" class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2">Ormawa</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-gray-500 md:ml-2">Edit</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="overflow-hidden bg-white rounded-lg shadow-md">
        <div class="p-6">
            <h2 class="mb-6 text-2xl font-semibold text-gray-900">Edit Data Ormawa</h2>

            @if ($errors->any())
            <div class="px-4 py-3 mb-6 text-red-700 bg-red-100 border border-red-400 rounded">
                <strong>Whoops!</strong> Terdapat beberapa masalah dengan input Anda.<br><br>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.ormawa.update', $ormawa->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 mt-4">
                    <div>
                        <label for="namaMahasiswa" class="block text-sm font-medium text-gray-700">Nama Mahasiswa</label>
                        <input type="text" name="namaMahasiswa" id="namaMahasiswa" value="{{ old('namaMahasiswa', $ormawa->namaMahasiswa) }}"
                            class="block w-full px-4 py-3 mt-1 text-base border-2 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="namaOrmawa" class="block text-sm font-medium text-gray-700">Nama Ormawa</label>
                        <input type="text" name="namaOrmawa" id="namaOrmawa" value="{{ old('namaOrmawa', $ormawa->namaOrmawa) }}"
                            class="block w-full px-4 py-3 mt-1 text-base border-2 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                        <input type="text" name="nim" id="nim" value="{{ old('nim', $ormawa->nim) }}"
                            class="block w-full px-4 py-3 mt-1 text-base border-2 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $ormawa->email) }}"
                            class="block w-full px-4 py-3 mt-1 text-base border-2 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="noHp" class="block text-sm font-medium text-gray-700">No. HP</label>
                        <input type="text" name="noHp" id="noHp" value="{{ old('noHp', $ormawa->noHp) }}"
                            class="block w-full px-4 py-3 mt-1 text-base border-2 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" name="password" id="password"
                            class="block w-full px-4 py-3 mt-1 text-base border-2 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="profile" class="block text-sm font-medium text-gray-700">Foto Profil</label>
                        @if($ormawa->profile)
                            <div class="mt-2">
                                <img src="{{ asset('profiles/' . $ormawa->profile) }}" alt="Current Profile" class="object-cover w-32 h-32 rounded-lg">
                            </div>
                        @endif
                        <input type="file" name="profile" id="profile" class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <a href="{{ route('admin.ormawa.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-800 bg-gray-200 rounded-md hover:bg-gray-300">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
