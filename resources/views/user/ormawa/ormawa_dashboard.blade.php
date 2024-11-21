@extends('layouts.ormawa')
@section('title', 'Dashboard Ormawa')
@section('content')
  <div class="container flex-grow max-w-5xl px-4 mx-auto mt-8">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
      <!-- Surat yang diajukan -->
      <a href="{{ route('ormawa.riwayat', ['status' => 'diajukan']) }}" class="block">
        <div class="p-4 bg-yellow-400 rounded-lg shadow">
          <div class="flex items-center">
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <!-- Envelope icon SVG -->
              <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
              <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
            </svg>
            <h2 class="text-lg font-bold">{{ $countDiajukan }} Surat yang diajukan</h2>
          </div>
        </div>
      </a>

      <!-- Surat sudah disahkan -->
      <a href="{{ route('ormawa.riwayat', ['status' => 'disahkan']) }}" class="block">
        <div class="p-4 bg-green-400 rounded-lg shadow">
          <div class="flex items-center">
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <!-- Checkmark envelope icon SVG -->
              <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
              <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
              <path d="M9.293 12.293a1 1 0 011.414 0L12 13.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"></path>
            </svg>
            <h2 class="text-lg font-bold">{{ $countDisahkan }} Surat sudah disahkan</h2>
          </div>
        </div>
      </a>

      <!-- Surat perlu direvisi -->
      <a href="{{ route('ormawa.riwayat', ['status' => 'direvisi']) }}" class="block">
        <div class="p-4 bg-blue-400 rounded-lg shadow">
          <div class="flex items-center">
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <!-- Pencil envelope icon SVG -->
              <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
              <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
              <path d="M13.293 3.293a1 1 0 011.414 0l2 2a1 1 0 010 1.414l-9 9a1 1 0 01-.39.242l-3 1a1 1 0 01-1.266-1.265l1-3a1 1 0 01.242-.391l9-9z"></path>
            </svg>
            <h2 class="text-lg font-bold">{{ $countRevisi }} Surat perlu direvisi</h2>
          </div>
        </div>
      </a>
    </div>

    <div class="mt-8">
      <div class="flex flex-col items-center justify-between mb-4 space-y-2 md:flex-row md:space-y-0">
        <div class="relative w-full md:w-64">
          <form method="GET" action="{{ route('ormawa.dashboard') }}" class="flex">
            <div class="relative flex-grow">
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Surat" class="w-full py-2 pl-10 pr-4 border rounded-l-lg">
              <svg class="absolute w-5 h-5 text-gray-400 left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
            <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-r-lg hover:bg-blue-600">
              Cari
            </button>
          </form>
        </div>
        <div>
          <form method="GET" action="{{ route('ormawa.dashboard') }}">
              <div>
                  <select name="status" class="px-4 py-2 border rounded-lg" onchange="this.form.submit()">
                      <option value="">Semua Status</option>
                      <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                      <option value="ditandatangani" {{ request('status') == 'ditandatangani' ? 'selected' : '' }}>Disahkan</option>
                      <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Revisi</option>
                  </select>
              </div>
          </form>
      </div>
      </div>
      <div class="p-4 bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">No. Surat</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tanggal Pengajuan</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Hal</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Kepada/Tujuan</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($dokumens as $dokumen)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $dokumen->nomor_surat }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $dokumen->tanggal_pengajuan }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $dokumen->perihal }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $dokumen->dosen->nama_dosen }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClass = match($dokumen->status_dokumen) {
                                'diajukan' => 'bg-yellow-100 text-yellow-800',
                                'disahkan' => 'bg-green-100 text-green-800',
                                'direvisi' => 'bg-blue-100 text-blue-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ ucfirst($dokumen->status_dokumen) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900" onclick="showModal({{ $dokumen->id }}, '{{ asset('storage/' . $dokumen->file) }}')">Lihat Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div id="detailModal" class="fixed inset-0 z-10 hidden overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen">
          <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-xl">
              <div class="flex items-center justify-between">
                  <h3 class="text-lg font-medium text-gray-900">Detail Dokumen</h3>
                  <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                      <span class="sr-only">Close</span>
                      &times;
                  </button>
              </div>
              <div class="mt-4">
                  <!-- Document Display Area -->
                  <div class="p-4 mb-4 bg-gray-100 border border-blue-500 rounded-lg">
                      <p id="modalContent" class="text-center">Loading...</p>
                  </div>
                  <!-- Buttons -->
                  <div class="flex justify-between">
                      <button class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                          DOWNLOAD
                      </button>
                      <button class="px-4 py-2 text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">
                          LIHAT
                      </button>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <script>
      function showModal(dokumenId, fileUrl) {
          document.getElementById('modalContent').innerHTML = `<iframe src="${fileUrl}" width="100%" height="500px"></iframe>`;
          document.getElementById('detailModal').classList.remove('hidden');

          // Update the "LIHAT" button to open the document in a new tab
          const lihatButton = document.querySelector('#detailModal .bg-yellow-500');
          lihatButton.onclick = function() {
              window.open(fileUrl, '_blank');
          };

          // Update the "DOWNLOAD" button to download the document
          const downloadButton = document.querySelector('#detailModal .bg-blue-500');
          downloadButton.onclick = function() {
              const link = document.createElement('a');
              link.href = fileUrl;
              link.download = fileUrl.split('/').pop(); // Extracts the file name from the URL
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
          };
      }

      function closeModal() {
          document.getElementById('detailModal').classList.add('hidden');
      }
  </script>
@endsection
