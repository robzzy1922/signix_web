@extends('layouts.admin.app')

@section('title', 'Semua Dokumen')

@section('content')
<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="inline-block py-2 min-w-full">
            <div class="overflow-hidden bg-white rounded-lg shadow-sm">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Daftar Dokumen Mahasiswa</h2>
                </div>

                <!-- Add this right after the header div and before the table -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <form method="GET" action="{{ route('admin.dokumen.index') }}" class="flex gap-4">
                        <!-- Search input -->
                        <div class="flex-1">
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Cari berdasarkan nama, NIM, judul dokumen..."
                                   class="px-4 py-2 w-full rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Status filter -->
                        <div class="w-48">
                            <select name="status"
                                    onchange="this.form.submit()"
                                    class="px-4 py-2 w-full rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Status</option>
                                <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                <option value="disahkan" {{ request('status') == 'disahkan' ? 'selected' : '' }}>Disahkan</option>
                                <option value="direvisi" {{ request('status') == 'direvisi' ? 'selected' : '' }}>Direvisi</option>
                            </select>
                        </div>

                        <!-- Per page selector -->
                        <div class="w-32">
                            <select name="per_page"
                                    onchange="this.form.submit()"
                                    class="px-4 py-2 w-full rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 per hal</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per hal</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per hal</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per hal</option>
                            </select>
                        </div>

                        <!-- Reset button -->
                        @if(request('search') || request('status') || request('per_page'))
                            <a href="{{ route('admin.dokumen.index') }}"
                               class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 focus:ring-4 focus:ring-gray-200">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-800">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-medium">No</th>
                                <th scope="col" class="px-6 py-4 font-medium">Tanggal</th>
                                <th scope="col" class="px-6 py-4 font-medium">Nomor Surat</th>
                                <th scope="col" class="px-6 py-4 font-medium">Pengaju</th>
                                <th scope="col" class="px-6 py-4 font-medium">Ormawa</th>
                                <th scope="col" class="px-6 py-4 font-medium">Perihal</th>
                                <th scope="col" class="px-6 py-4 font-medium">Dosen Tujuan</th>
                                <th scope="col" class="px-6 py-4 font-medium">Status</th>
                                <th scope="col" class="px-6 py-4 font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($dokumens as $doc)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $doc->tanggal_pengajuan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $doc->nomor_surat }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $doc->ormawa?->namaMahasiswa ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $doc->ormawa?->namaOrmawa ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $doc->perihal }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $doc->dosen?->nama_dosen ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                        {{ $doc->status_dokumen == 'diajukan' ? 'bg-yellow-100 text-yellow-800' :
                                           ($doc->status_dokumen == 'disahkan' ? 'bg-green-100 text-green-800' :
                                           ($doc->status_dokumen == 'direvisi' ? 'bg-blue-100 text-blue-800' :
                                           'bg-red-100 text-red-800')) }}">
                                        {{ ucfirst($doc->status_dokumen) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button onclick="openModal({{ $doc->id }})"
                                            class="px-3 py-1.5 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada dokumen yang diajukan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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

                <!-- Pagination -->
                @if($dokumens->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <!-- Previous Page Link -->
                        <div class="flex flex-1 justify-start">
                            @if($dokumens->onFirstPage())
                                <span class="inline-flex relative items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white rounded-md border border-gray-300 cursor-default">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $dokumens->previousPageUrl() }}" class="inline-flex relative items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-md border border-gray-300 hover:bg-gray-50">
                                    Previous
                                </a>
                            @endif
                        </div>

                        <!-- Page Numbers -->
                        <div class="hidden md:flex">
                            @foreach($dokumens->getUrlRange(1, $dokumens->lastPage()) as $page => $url)
                                @if($page == $dokumens->currentPage())
                                    <span class="inline-flex relative items-center px-4 py-2 mx-1 text-sm font-medium text-white bg-blue-600 rounded-md border border-gray-300 cursor-default">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex relative items-center px-4 py-2 mx-1 text-sm font-medium text-gray-700 bg-white rounded-md border border-gray-300 hover:bg-gray-50">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        </div>

                        <!-- Next Page Link -->
                        <div class="flex flex-1 justify-end">
                            @if($dokumens->hasMorePages())
                                <a href="{{ $dokumens->nextPageUrl() }}" class="inline-flex relative items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-md border border-gray-300 hover:bg-gray-50">
                                    Next
                                </a>
                            @else
                                <span class="inline-flex relative items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white rounded-md border border-gray-300 cursor-default">
                                    Next
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Pagination Information -->
                    <div class="mt-4 text-sm text-center text-gray-500">
                        Showing {{ $dokumens->firstItem() ?? 0 }} to {{ $dokumens->lastItem() ?? 0 }} of {{ $dokumens->total() }} results
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Script untuk modal -->
<script>
let currentDocumentId = null;
let currentFileUrl = null;

function openModal(documentId) {
    currentDocumentId = documentId;

    // Fetch document details
    fetch(`/admin/dokumen/${documentId}`)
        .then(response => response.json())
        .then(data => {
            currentFileUrl = `/storage/${data.file}`;
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
                        <p class="text-sm font-medium text-gray-500">Pengaju</p>
                        <p class="mt-1">${data.ormawa?.nama || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Perihal</p>
                        <p class="mt-1">${data.perihal}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Dosen Tujuan</p>
                        <p class="mt-1">${data.dosen?.nama || 'N/A'}</p>
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
