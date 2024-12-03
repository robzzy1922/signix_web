@extends('layouts.ormawa')
@section('title', 'Dashboard Ormawa')
@section('content')
  <div class="container flex-grow px-4 mx-auto mt-8 max-w-5xl">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
      <!-- Surat yang diajukan -->
      <a href="{{ route('ormawa.riwayat', ['status' => 'diajukan']) }}" class="block">
        <div class="p-4 bg-yellow-400 rounded-lg shadow">
          <div class="flex items-center">
            <svg class="mr-2 w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
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
            <svg class="mr-2 w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
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
            <svg class="mr-2 w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
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
      <div class="flex flex-col justify-between items-center mb-4 space-y-2 md:flex-row md:space-y-0">
        <div class="relative w-full md:w-64">
          <form method="GET" action="{{ route('ormawa.dashboard') }}" class="flex">
            <div class="relative flex-grow">
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Surat" class="py-2 pr-4 pl-10 w-full rounded-l-lg border">
              <svg class="absolute top-3 left-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                  <select name="status" class="px-4 py-2 rounded-lg border" onchange="this.form.submit()">
                      <option value="">Semua Status</option>
                      <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                      <option value="disahkan" {{ request('status') == 'disahkan' ? 'selected' : '' }}>Disahkan</option>
                      <option value="direvisi" {{ request('status') == 'direvisi' ? 'selected' : '' }}>Revisi</option>
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
                @if ($dokumens->isEmpty())
                        <tr>
                            <td colspan="6" class="py-8 text-center">
                                <div class="flex flex-col justify-center items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4V12M12 16H12.01M18.364 5.636L15 9M21 12H15M9 12H3M6.636 5.636L10 9M12 12L12 20M6.636 18.364L10 15M18.364 18.364L15 15"></path>
                                    </svg>
                                    <p class="mt-2 text-gray-600">Anda belum memiliki pengajuan surat.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
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
  <div id="detailModal" class="hidden overflow-y-auto fixed inset-0 z-50">
      <div class="flex justify-center items-center px-4 min-h-screen">
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

          <div class="relative w-full max-w-lg bg-white rounded-lg shadow-xl">
              <div class="px-6 py-4 border-b border-gray-200">
                  <h3 class="text-lg font-medium text-gray-900">Detail Dokumen</h3>
                  <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
                      <span class="sr-only">Close</span>
                      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                  </button>
              </div>

              <div class="px-6 py-4" id="modalContent">
                  <!-- Content will be loaded here -->
              </div>

              <div class="flex justify-end px-6 py-4 space-x-3 border-t border-gray-200">
                  <button onclick="downloadDocument()"
                          class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                      Download
                  </button>
                  <button onclick="viewDocument()"
                          class="px-4 py-2 text-white bg-yellow-500 rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                      Lihat
                  </button>
                  <button onclick="closeModal()"
                          class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                      Tutup
                  </button>
              </div>
          </div>
      </div>
  </div>

  <script>
      let currentDocumentId = null;
      let currentFileUrl = null;

      function showModal(dokumenId, fileUrl) {
          currentDocumentId = dokumenId;
          currentFileUrl = fileUrl;

          // Tampilkan loading state
          document.getElementById('modalContent').innerHTML = `
              <div class="flex justify-center items-center h-64">
                  <div class="w-12 h-12 rounded-full border-b-2 border-blue-500 animate-spin"></div>
              </div>
          `;

          document.getElementById('detailModal').classList.remove('hidden');

          // Gunakan route yang benar
          fetch(`/ormawa/dokumen/${dokumenId}`)
              .then(response => {
                  if (!response.ok) {
                      throw new Error('Network response was not ok');
                  }
                  return response.json();
              })
              .then(data => {
                  console.log('Received data:', data); // Untuk debugging
                  document.getElementById('modalContent').innerHTML = `
                      <div class="space-y-4">
                          <div class="p-4 mb-4 bg-gray-100 rounded-lg border border-blue-500">
                              <object
                                  data="${data.file_url}#toolbar=0"
                                  type="application/pdf"
                                  width="100%"
                                  height="500px"
                                  class="rounded-lg border"
                              >
                                  <p>Dokumen tidak dapat ditampilkan.
                                     <a href="${data.file_url}" target="_blank" class="text-blue-500">Klik disini untuk membuka</a>
                                  </p>
                              </object>
                          </div>
                          <div>
                              <p class="text-sm font-medium text-gray-500">Nomor Surat</p>
                              <p class="mt-1">${data.nomor_surat || '-'}</p>
                          </div>
                          <div>
                              <p class="text-sm font-medium text-gray-500">Tanggal Pengajuan</p>
                              <p class="mt-1">${data.tanggal_pengajuan || '-'}</p>
                          </div>
                          <div>
                              <p class="text-sm font-medium text-gray-500">Perihal</p>
                              <p class="mt-1">${data.perihal || '-'}</p>
                          </div>
                          <div>
                              <p class="text-sm font-medium text-gray-500">Status</p>
                              <p class="mt-1">${data.status_dokumen || '-'}</p>
                          </div>
                          <div>
                              <p class="text-sm font-medium text-gray-500">Keterangan</p>
                              <p class="mt-1">${data.keterangan || '-'}</p>
                          </div>
                      </div>
                  `;
              })
              .catch(error => {
                  console.error('Error:', error);
                  document.getElementById('modalContent').innerHTML = `
                      <div class="text-center text-red-500">
                          Terjadi kesalahan saat memuat data. Silakan coba lagi.
                      </div>
                  `;
              });
      }

      function closeModal() {
          document.getElementById('detailModal').classList.add('hidden');
          currentDocumentId = null;
          currentFileUrl = null;
      }

      function downloadDocument() {
          if (currentFileUrl) {
              const link = document.createElement('a');
              link.href = currentFileUrl;
              link.download = currentFileUrl.split('/').pop();
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
          }
      }

      function viewDocument() {
          if (currentFileUrl) {
              window.open(currentFileUrl, '_blank');
          }
      }

      // Close modal when clicking outside
      document.getElementById('detailModal').addEventListener('click', function(e) {
          if (e.target === this) {
              closeModal();
          }
      });
  </script>
@endsection
