@extends('layouts.ormawa')
@section('title', 'Riwayat Pengajuan')
@section('content')
    <div class="container flex-grow max-w-5xl px-4 mx-auto mt-8">
        <h1 class="mb-6 text-2xl font-bold">Riwayat Pengajuan</h1>

        <div class="flex items-center justify-between mb-4">
            <div class="relative w-64">
                <input type="text" placeholder="Cari Pengajuan" class="w-full py-2 pl-10 pr-4 border rounded-lg">
                <svg class="absolute w-5 h-5 text-gray-400 left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <div>
                <form method="GET" action="{{ route('ormawa.riwayat') }}">
                    <div>
                        <select name="status" class="px-4 py-2 border rounded-lg" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                            <option value="disahkan" {{ request('status') == 'disahkan' ? 'selected' : '' }}>Disahkan</option>
                            <option value="direvisi" {{ request('status') == 'direvisi' ? 'selected' : '' }}>Revisi</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="overflow-hidden bg-white rounded-lg shadow-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">No. Surat</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tanggal Pengajuan</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Hal</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tujuan</th>
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
                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
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

        <div class="flex justify-center mt-4">
            <!-- Pagination component -->
            <nav class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                <a href="#" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
                    <span class="sr-only">Previous</span>
                    <!-- Heroicon name: solid/chevron-left -->
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">
                    1
                </a>
                <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">
                    2
                </a>
                <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">
                    3
                </a>
                <a href="#" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">
                    <span class="sr-only">Next</span>
                    <!-- Heroicon name: solid/chevron-right -->
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            </nav>
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
