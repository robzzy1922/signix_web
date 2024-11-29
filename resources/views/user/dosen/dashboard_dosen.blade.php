@extends('layouts.dosen')
@section('title', 'Dashboard Dosen')
@section('content')
    <div class="container px-4 py-8 mx-auto">
        <div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-3">
            <!-- Surat yang diajukan -->
            <a href="{{ route('dosen.riwayat', ['status' => 'diajukan']) }}" class="block">
                <div class="p-4 bg-yellow-400 rounded-lg shadow hover:bg-yellow-500 transition-colors">
                    <div class="flex items-center">
                        <i class="mr-2 text-2xl fas fa-envelope"></i>
                        <h2 class="text-lg font-bold">{{ $countDiajukan }} Surat diajukan</h2>
                    </div>
                </div>
            </a>

            <!-- Surat sudah tertanda -->
            <a href="{{ route('dosen.riwayat', ['status' => 'disahkan']) }}" class="block">
                <div class="p-4 bg-green-400 rounded-lg shadow hover:bg-green-500 transition-colors">
                    <div class="flex items-center">
                        <i class="mr-2 text-2xl fas fa-check-circle"></i>
                        <h2 class="text-lg font-bold">{{ $countDisahkan }} Surat sudah tertanda</h2>
                    </div>
                </div>
            </a>

            <!-- Surat perlu direvisi -->
            <a href="{{ route('dosen.riwayat', ['status' => 'direvisi']) }}" class="block">
                <div class="p-4 bg-blue-400 rounded-lg shadow hover:bg-blue-500 transition-colors">
                    <div class="flex items-center">
                        <i class="mr-2 text-2xl fas fa-edit"></i>
                        <h2 class="text-lg font-bold">{{ $countRevisi }} Surat perlu direvisi</h2>
                    </div>
                </div>
            </a>
        </div>

        <!-- Search and Filter Section -->
        <div class="flex flex-col justify-between items-center mb-8 space-y-4 md:flex-row md:space-y-0">
            <div class="w-full md:w-64">
                <form method="GET" action="{{ route('dosen.dashboard') }}" class="flex">
                    <div class="relative flex-grow">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari Surat"
                               class="py-2 pr-4 pl-10 w-full rounded-l-lg border">
                        <i class="absolute top-3 left-3 text-gray-400 fas fa-search"></i>
                    </div>
                    <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-r-lg hover:bg-blue-600">
                        Cari
                    </button>
                </form>
            </div>
            <div>
                <form method="GET" action="{{ route('dosen.dashboard') }}">
                    <select name="status" class="px-4 py-2 rounded-lg border" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="disahkan" {{ request('status') == 'disahkan' ? 'selected' : '' }}>Tertanda</option>
                        <option value="direvisi" {{ request('status') == 'direvisi' ? 'selected' : '' }}>Revisi</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Table Section -->
        <div class="p-4 bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">No. Surat</th>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Tanggal Pengajuan</th>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Hal</th>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Dari</th>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if ($dokumens->where('dosen_id', Auth::id())->isEmpty())
                        <tr>
                            <td colspan="6" class="py-8 text-center">
                                <div class="flex flex-col justify-center items-center">
                                    <i class="text-4xl text-gray-400 fas fa-inbox"></i>
                                    <p class="mt-2 text-gray-600">Tidak ada data yang tersedia.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                    @foreach($dokumens->where('dosen_id', Auth::id()) as $dokumen)
                        <tr data-id="{{ $dokumen->id }}">
                            <td class="px-6 py-4 whitespace-nowrap" data-nomor>{{ $dokumen->nomor_surat }}</td>
                            <td class="px-6 py-4 whitespace-nowrap" data-tanggal>{{ $dokumen->tanggal_pengajuan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap" data-perihal>{{ $dokumen->perihal }}</td>
                            <td class="px-6 py-4 whitespace-nowrap" data-ormawa>{{ $dokumen->ormawa->nama_ormawa }}</td>
                            <td class="px-6 py-4 whitespace-nowrap" data-status>
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
                                <a href="#" class="text-indigo-600 hover:text-indigo-900"
                                   onclick="showModal({{ $dokumen->id }}, '{{ asset('storage/' . $dokumen->file) }}')">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="documentModal" class="hidden overflow-y-auto fixed inset-0 z-50">
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

    function showModal(documentId, pdfUrl) {
        currentDocumentId = documentId;
        currentFileUrl = pdfUrl;

        // Fetch document details
        fetch(`/dosen/dokumen/${documentId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalContent').innerHTML = `
                    <div class="space-y-4">
                        <div class="p-4 mb-4 bg-gray-100 rounded-lg border border-blue-500">
                            <iframe src="${currentFileUrl}" width="100%" height="500px"></iframe>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nomor Surat</p>
                            <p class="mt-1">${data.nomor_surat}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tanggal Pengajuan</p>
                            <p class="mt-1">${data.tanggal_pengajuan}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Perihal</p>
                            <p class="mt-1">${data.perihal}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status</p>
                            <p class="mt-1">${data.status_dokumen}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Keterangan</p>
                            <p class="mt-1">${data.keterangan || '-'}</p>
                        </div>
                    </div>
                `;
            });

        document.getElementById('documentModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('documentModal').classList.add('hidden');
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
    document.getElementById('documentModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    </script>
@endsection
