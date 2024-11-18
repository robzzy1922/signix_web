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
          <h2 class="text-lg font-bold">{{ $countDiajukan }} Surat yang diajukan</h2>
        </div>
      </div>

      <!-- Surat sudah disahkan -->
      <div class="bg-green-400 p-4 rounded-lg shadow">
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

      <!-- Surat perlu direvisi -->
      <div class="bg-blue-400 p-4 rounded-lg shadow">
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
    </div>

    <div class="mt-8">
      <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-2 md:space-y-0">
        <div class="relative w-full md:w-64">
          <input type="text" placeholder="Cari Surat" class="w-full pl-10 pr-4 py-2 border rounded-lg">
          <svg class="w-5 h-5 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
        <div>
          <form method="GET" action="{{ route('ormawa.dashboard') }}">
              <div>
                  <select name="status" class="border rounded-lg px-4 py-2" onchange="this.form.submit()">
                      <option value="">Semua Status</option>
                      <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                      <option value="ditandatangani" {{ request('status') == 'ditandatangani' ? 'selected' : '' }}>Disahkan</option>
                      <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Revisi</option>
                  </select>
              </div>
          </form>
      </div>
      </div>
      <div class="bg-white p-4 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Surat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kepada/Tujuan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
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
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            {{ ucfirst($dokumen->status_dokumen) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
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
  <div id="detailModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
      <div class="flex items-center justify-center min-h-screen">
          <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
              <div class="flex justify-between items-center">
                  <h3 class="text-lg font-medium text-gray-900">Detail Dokumen</h3>
                  <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                      <span class="sr-only">Close</span>
                      &times;
                  </button>
              </div>
              <div class="mt-4">
                  <!-- Document Display Area -->
                  <div class="bg-gray-100 border border-blue-500 rounded-lg p-4 mb-4">
                      <p id="modalContent" class="text-center">Loading...</p>
                  </div>
                  <!-- Buttons -->
                  <div class="flex justify-between">
                      <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                          DOWNLOAD
                      </button>
                      <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">
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