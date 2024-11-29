@extends('layouts.dosen')
@section('title', 'Buat Tanda Tangan')
@section('content')
    <div class="container mx-auto mt-20 text-center">
        <h1 class="text-4xl font-bold">Tanda Tangani Dokumen</h1>
        <p class="mt-4 text-lg">Alat untuk menandatangani dokumen secara elektronik. Tanda tangani dokumen sendiri atau tanda tangani dokumen orang lain.</p>

        <div class="mt-12">
            <label class="inline-block">
                <span class="sr-only">Choose File</span>
                <input type="file" class="hidden" />
                <div class="px-8 py-4 text-xl font-bold text-white bg-yellow-500 rounded-full cursor-pointer hover:bg-yellow-600">
                    PILIH FILE
                </div>
            </label>
            <p class="mt-4 text-sm text-gray-500">Atau jatuhkan file di sini</p>
        </div>
    </div>
    <br>
@endsection
