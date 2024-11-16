@extends('layouts.ormawa')
@section('title', 'Dashboard Ormawa')
@section('content')
  <div class="container mx-auto px-4 mt-8 max-w-5xl flex-grow">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <!-- Surat yang diajukan -->
      <div class="bg-yellow-400 p-4 rounded-lg shadow">
        <div class="flex items-center">
          <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <!-- Envelope icon SVG -->
            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
          </svg>
          <h2 class="text-lg font-bold">30 Surat yang diajukan</h2>
        </div>
      </div>

      <!-- Surat sudah tertanda -->
      <div class="bg-green-400 p-4 rounded-lg shadow">
        <div class="flex items-center">
          <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <!-- Checkmark envelope icon SVG -->
            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
            <path d="M9.293 12.293a1 1 0 011.414 0L12 13.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"></path>
          </svg>
          <h2 class="text-lg font-bold">10 Surat sudah tertanda</h2>
        </div>
      </div>

      <!-- Surat perlu direvisi -->
      <div class="bg-blue-400 p-4 rounded-lg shadow">
        <div class="flex items-center">
          <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <!-- Pencil envelope icon SVG -->
            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
            <path d="M13.293 3.293a1 1 0 011.414 0l2 2a1 1 0 010 1.414l-9 9a1 1 0 01-.39.242l-3 1a1 1 0 01-1.266-1.265l1-3a1 1 0 01.242-.391l9-9z"></path>
          </svg>
          <h2 class="text-lg font-bold">1 Surat perlu direvisi</h2>
        </div>
      </div>
    </div>

    <div class="mt-8">
      <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-2 md:space-y-0">
        <div class="relative w-full md:w-64">
          <input type="text" placeholder="Cari Surat" class="w-full pl-10 pr-4 py-2 border rounded-lg">
          <svg class="w-5 h-5 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
        <select class="w-full md:w-auto border rounded-lg px-4 py-2">
          <option>Tertanda</option>
          <!-- Add other status options here -->
        </select>
      </div>
      <div class="bg-white p-4 rounded-lg shadow">
        <!-- Table or list of documents will go here -->
      </div>
    </div>
  </div>
@endsection