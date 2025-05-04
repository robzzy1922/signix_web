@extends('layouts.ormawa')
@section('title', 'Dashboard Ormawa')
@section('content')
<div class="container flex-grow px-4 mx-auto mt-8 max-w-5xl">
    <!-- Alert Success -->
    @if(session('success'))
    <div id="alert-success" class="relative p-4 mb-6 text-green-700 bg-green-100 rounded-lg border border-green-400">
        <div class="flex items-center">
            <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">Berhasil!</span>
            <span class="ml-2">{{ session('success') }}</span>
        </div>
        <!-- Progress bar -->
        <div class="absolute bottom-0 left-0 w-full h-1 bg-green-200">
            <div id="progress-bar" class="h-1 bg-green-600 transition-all duration-[5000ms] ease-linear w-full"></div>
        </div>
    </div>

    <script>
        // Handle alert animation
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('alert-success');
            const progressBar = document.getElementById('progress-bar');

            if (alert) {
                // Start progress bar animation
                setTimeout(() => {
                    progressBar.style.width = '0%';
                }, 100);

                // Hide alert after animation
                setTimeout(() => {
                    alert.style.transform = 'translateY(-100%)';
                    alert.style.opacity = '0';
                    alert.style.transition = 'all 300ms ease-in-out';

                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            }
        });
    </script>
    @endif

    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <!-- Dokumen Diajukan -->
        <a href="{{ route('ormawa.riwayat', ['status' => 'diajukan']) }}" class="block">
            <div
                class="p-6 bg-yellow-400 rounded-xl shadow-lg duration-300 hover:shadow-xl hover:bg-yellow-500 transition-color">
                <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h2 class="text-xl font-bold text-white">Dokumen Diajukan</h2>
                        </div>
                        <span class="text-4xl font-bold text-white">{{ $countDiajukan }}</span>
                    </div>
                </div>
            </div>
        </a>

        <!-- Dokumen Tertanda -->
        <a href="{{ route('ormawa.riwayat', ['status' => 'disahkan']) }}" class="block">
            <div
                class="p-6 bg-green-400 rounded-xl shadow-lg duration-300 hover:shadow-xl hover:bg-green-500 transition-color">
                <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h2 class="text-xl font-bold text-white">Dokumen Tertanda</h2>
                        </div>
                        <span class="text-4xl font-bold text-white">{{ $countDisahkan }}</span>
                    </div>
                </div>
            </div>
        </a>

        <!-- Perlu Direvisi -->
        <a href="{{ route('ormawa.riwayat', ['status' => 'butuh_revisi']) }}" class="block">
            <div
                class="p-6 bg-red-400 rounded-xl shadow-lg duration-300 hover:shadow-xl hover:bg-red-500 transition-color">
                <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <h2 class="text-xl font-bold text-white">Perlu Direvisi</h2>
                        </div>
                        <span class="text-4xl font-bold text-white">{{ $countButuhRevisi }}</span>
                    </div>
                </div>
            </div>
        </a>

        <!-- Sudah Direvisi -->
        <a href="{{ route('ormawa.riwayat', ['status' => 'direvisi']) }}" class="block">
            <div
                class="p-6 bg-blue-400 rounded-xl shadow-lg duration-300 hover:shadow-xl hover:bg-blue-500 transition-color">
                <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <h2 class="text-xl font-bold text-white">Sudah Direvisi</h2>
                        </div>
                        <span class="text-4xl font-bold text-white">{{ $countRevisi }}</span>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="mt-8">
        <div class="flex flex-col justify-between items-center mb-4 space-y-2 md:flex-row md:space-y-0">
            <div class="relative w-full md:w-64">
                <form method="GET" action="{{ route('ormawa.dashboard') }}" class="flex">
                    <div class="relative flex-grow">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Surat"
                            class="py-2 pr-4 pl-10 w-full rounded-l-lg border">
                        <svg class="absolute top-3 left-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
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
                            <option value="diajukan" {{ request('status')=='diajukan' ? 'selected' : '' }}>Diajukan
                            </option>
                            <option value="disahkan" {{ request('status')=='disahkan' ? 'selected' : '' }}>Disahkan
                            </option>
                            <option value="butuh_revisi" {{ request('status')=='butuh_revisi' ? 'selected' : '' }}>Perlu
                                Direvisi</option>
                            <option value="direvisi" {{ request('status')=='direvisi' ? 'selected' : '' }}>Revisi
                            </option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto p-4 w-full bg-white rounded-lg shadow">
            <!-- Added overflow-x-auto -->
            <div class="overflow-x-auto min-w-full">
                <!-- Added wrapper div -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                No. Surat</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Tanggal Pengajuan</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Hal</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Kepada/Tujuan</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Sebagai</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Status</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if ($dokumens->isEmpty())
                        <tr>
                            <td colspan="6" class="py-8 text-center">
                                <div class="flex flex-col justify-center items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4V12M12 16H12.01M18.364 5.636L15 9M21 12H15M9 12H3M6.636 5.636L10 9M12 12L12 20M6.636 18.364L10 15M18.364 18.364L15 15">
                                        </path>
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($dokumen->dosen)
                                {{ $dokumen->dosen->nama_dosen }}
                                @elseif ($dokumen->kemahasiswaan)
                                {{ $dokumen->kemahasiswaan->nama_kemahasiswaan }}
                                @else
                                N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($dokumen->dosen)
                                Dosen
                                @elseif ($dokumen->kemahasiswaan)
                                Kemahasiswaan
                                @else
                                N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                $statusClass = match($dokumen->status_dokumen) {
                                'diajukan' => 'bg-yellow-100 text-yellow-800',
                                'disahkan' => 'bg-green-100 text-green-800',
                                'butuh revisi' => 'bg-red-100 text-red-800',
                                'sudah direvisi' => 'bg-blue-100 text-blue-800',
                                'ditolak' => 'bg-red-100 text-red-800',
                                'disetujui' => 'bg-green-100 text-green-800',
                                'revisi' => 'bg-orange-100 text-orange-800',
                                default => 'bg-gray-100 text-gray-800'
                                };
                                @endphp
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ ucfirst($dokumen->status_dokumen) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900"
                                   onclick="showModal({{ $dokumen->id }})">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="detailModal" class="hidden overflow-y-auto fixed inset-0 z-50">
    <div class="flex justify-center items-center px-4 pt-4 pb-20 min-h-screen text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block overflow-hidden text-left align-bottom bg-white rounded-lg shadow-xl transition-all transform sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                <div class="flex justify-between items-center pb-4 mb-4 border-b">
                    <h3 class="text-2xl font-semibold text-gray-900">Detail Dokumen</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div id="modalContent" class="space-y-4">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let currentDocumentId = null;
    let currentFileUrl = null;

    function showModal(documentId) {
        currentDocumentId = documentId;

        // Show loading state
        document.getElementById('modalContent').innerHTML = `
            <div class="flex justify-center items-center py-8">
                <div class="w-8 h-8 rounded-full border-b-2 border-blue-500 animate-spin"></div>
                <span class="ml-2">Memuat dokumen...</span>
            </div>
        `;

        document.getElementById('detailModal').classList.remove('hidden');

        // Fetch document details
        fetch(`/ormawa/dokumen/${documentId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            // Log the raw response for debugging
            console.log('Response status:', response.status);

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            return response.json();
        })
        .then(response => {
            // Log the parsed response for debugging
            console.log('Response data:', response);

            if (!response.success) {
                throw new Error(response.message || 'Terjadi kesalahan saat memuat dokumen');
            }

            const data = response.data;
            currentFileUrl = data.file_url;

            // Check if document needs revision
            const needsRevision = data.status_dokumen.toLowerCase() === 'butuh revisi';

            // Update modal content with document details and preview
            document.getElementById('modalContent').innerHTML = `
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="mb-4 text-lg font-semibold">Informasi Dokumen</h4>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-600">Nomor Surat:</dt>
                                    <dd>${data.nomor_surat || '-'}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-600">Tanggal Pengajuan:</dt>
                                    <dd>${data.tanggal_pengajuan}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-600">Perihal:</dt>
                                    <dd>${data.perihal}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-600">Status:</dt>
                                    <dd>
                                        <span class="px-2 py-1 text-sm rounded-full ${getStatusClass(data.status_dokumen)}">
                                            ${data.status_dokumen}
                                        </span>
                                    </dd>
                                </div>
                                ${data.keterangan_revisi ? `
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-600">Keterangan Revisi:</dt>
                                    <dd class="text-red-600">${data.keterangan_revisi}</dd>
                                </div>
                                ` : ''}
                                ${data.tujuan ? `
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-600">Tujuan:</dt>
                                    <dd>${data.tujuan.nama} (${data.tujuan.jenis})</dd>
                                </div>
                                ` : ''}
                            </dl>
                        </div>

                        ${needsRevision ? `
                        <!-- Form Revisi Dokumen (hanya muncul jika status "butuh revisi") -->
                        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                            <h4 class="mb-4 text-lg font-semibold">Revisi Dokumen</h4>
                            <form id="revisionForm" class="space-y-3">
                                <div>
                                    <label for="dokumen" class="block text-sm font-medium text-gray-700">Unggah Dokumen Revisi (PDF)</label>
                                    <input type="file" id="dokumen" name="dokumen" accept=".pdf"
                                        class="block px-3 py-2 mt-1 w-full bg-white rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan Revisi (Opsional)</label>
                                    <textarea id="keterangan" name="keterangan" rows="3"
                                        class="block px-3 py-2 mt-1 w-full bg-white rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                                </div>
                                <button type="submit"
                                    class="inline-flex justify-center items-center px-4 py-2 w-full text-sm font-medium text-white bg-blue-600 rounded-md border border-transparent shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                                    </svg>
                                    Kirim Revisi
                                </button>
                            </form>
                        </div>
                        ` : ''}

                        <div class="flex flex-col space-y-2">
                            <!-- Tombol Download -->
                            <a href="/ormawa/dokumen/${currentDocumentId}/download"
                               class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md border border-transparent shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download Dokumen
                            </a>
                            <!-- Tombol Lihat di Tab Baru -->
                            <a href="/ormawa/dokumen/${currentDocumentId}/view"
                               target="_blank"
                               class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-md border border-gray-300 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat di Tab Baru
                            </a>
                        </div>
                    </div>
                    <div class="h-[600px] border rounded-lg overflow-hidden">
                        <iframe src="/ormawa/dokumen/${currentDocumentId}/view" class="w-full h-full" frameborder="0"></iframe>
                    </div>
                </div>
            `;

            // If the document needs revision, handle form submission
            if (needsRevision) {
                document.getElementById('revisionForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitRevision(currentDocumentId);
                });
            }
        })
        .catch(error => {
            console.error('Error loading document:', error);
            document.getElementById('modalContent').innerHTML = `
                <div class="py-8 text-center text-red-600">
                    ${error.message || 'Terjadi kesalahan saat memuat dokumen'}<br>
                    <span class="block mt-2 text-sm">Detail: ${error.toString()}</span>
                </div>
            `;
        });
    }

    function submitRevision(documentId) {
        const formData = new FormData();
        const fileInput = document.getElementById('dokumen');
        const keterangan = document.getElementById('keterangan').value;

        if (fileInput.files.length === 0) {
            alert('Silakan pilih file dokumen revisi');
            return;
        }

        formData.append('dokumen', fileInput.files[0]);
        formData.append('keterangan', keterangan);
        formData.append('_token', '{{ csrf_token() }}');

        // Show loading state
        const submitButton = document.querySelector('#revisionForm button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <div class="inline-flex items-center">
                <svg class="mr-2 -ml-1 w-4 h-4 text-white animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mengirim...
            </div>
        `;

        fetch(`/ormawa/dokumen/${documentId}/update`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            // Log the raw response for debugging
            console.log('Update response status:', response.status);

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            return response.json();
        })
        .then(response => {
            // Log the parsed response
            console.log('Update response data:', response);

            if (!response.success) {
                throw new Error(response.message || 'Terjadi kesalahan saat mengirim revisi');
            }

            // Close modal and refresh page to show updated status
            closeModal();

            // Show success message and reload page
            alert('Revisi dokumen berhasil dikirim');
            window.location.reload();
        })
        .catch(error => {
            console.error('Error updating document:', error);
            alert(`${error.message || 'Terjadi kesalahan saat mengirim revisi'}\nDetail: ${error.toString()}`);

            // Reset button
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        });
    }

    function closeModal() {
        document.getElementById('detailModal').classList.add('hidden');
        currentDocumentId = null;
        currentFileUrl = null;
    }

    function getStatusClass(status) {
        const statusClasses = {
            'diajukan': 'bg-yellow-100 text-yellow-800',
            'disahkan': 'bg-green-100 text-green-800',
            'butuh revisi': 'bg-red-100 text-red-800',
            'sudah direvisi': 'bg-blue-100 text-blue-800',
            'ditolak': 'bg-red-100 text-red-800',
            'disetujui': 'bg-green-100 text-green-800',
            'revisi': 'bg-orange-100 text-orange-800'
        };
        return statusClasses[status.toLowerCase()] || 'bg-gray-100 text-gray-800';
    }
</script>
@endsection
