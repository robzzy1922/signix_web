<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dokumen</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Optional: Add custom fonts (Google Fonts) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Add PDF.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 50;
        }

        .modal-content {
            position: relative;
            width: 90%;
            height: 90%;
            margin: 2% auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        #pdf-viewer {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
    <div class="py-16 min-h-screen bg-gray-50">
        <div class="container px-4 mx-auto">
            <div class="overflow-hidden mx-auto max-w-4xl bg-white rounded-2xl border border-gray-200 shadow-xl">
                @if(isset($verified) && $verified)
                    <div class="p-8">
                        <div class="text-center">
                            <div class="inline-flex justify-center items-center mb-6 w-20 h-20 bg-green-100 rounded-full">
                                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h1 class="mb-3 text-3xl font-bold text-gray-900">Dokumen Terverifikasi</h1>
                            <p class="text-gray-600">
                                Diverifikasi pada:
                                @if($dokumen->tanggal_verifikasi)
                                    {{ \Carbon\Carbon::parse($dokumen->tanggal_verifikasi)->setTimezone('Asia/Jakarta')->format('d M Y H:i:s') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                    <div class="pt-8 mt-10 border-t border-gray-200">
                        <h2 class="mb-8 text-2xl font-semibold text-gray-900">Detail Dokumen</h2>
                        <div class="space-y-6">
                            <div class="flex items-center">
                                <span class="w-1/3 text-gray-600">Kode Pengesahan:</span>
                                <div class="flex items-center space-x-2 w-2/3">
                                    <code class="flex-1 px-4 py-2 font-mono text-sm tracking-wider bg-gray-50 rounded-lg border border-gray-200">
                                        {{ $dokumen->kode_pengesahan }}
                                    </code>
                                    <button onclick="copyToClipboard('{{ $dokumen->kode_pengesahan }}')"
                                            class="p-2 text-gray-500 rounded-lg transition-colors hover:text-gray-700 hover:bg-gray-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <span class="w-1/3 text-gray-600">Nomor Surat:</span>
                                <span class="px-4 py-2 w-2/3 font-medium text-gray-900">{{ $dokumen->nomor_surat }}</span>
                            </div>

                            <div class="flex items-center">
                                <span class="w-1/3 text-gray-600">Tanggal Pengajuan:</span>
                                <span class="px-4 py-2 w-2/3 font-medium text-gray-900">{{ $dokumen->tanggal_pengajuan }}</span>
                            </div>

                            <div class="flex items-center">
                                <span class="w-1/3 text-gray-600">Perihal:</span>
                                <span class="px-4 py-2 w-2/3 font-medium text-gray-900">{{ $dokumen->perihal }}</span>
                            </div>

                            <div class="flex items-center">
                                <span class="w-1/3 text-gray-600">Status:</span>
                                <span class="px-4 py-2 w-2/3">
                                    <span class="inline-flex px-4 py-1.5 text-sm font-medium text-green-700 bg-green-100 rounded-full">
                                        {{ ucfirst($dokumen->status_dokumen) }}
                                    </span>
                                </span>
                            </div>

                            @if($dokumen->dosen)
                                <div class="flex items-center">
                                    <span class="w-1/3 text-gray-600">Disahkan oleh:</span>
                                    <span class="px-4 py-2 w-2/3 font-medium text-gray-900">
                                        @if ($dokumen->dosen)
                                        {{ $dokumen->dosen->nama_dosen }}
                                    @elseif ($dokumen->kemahasiswaan)
                                        {{ $dokumen->kemahasiswaan->nama_kemahasiswaan }}
                                    @else
                                        N/A
                                    @endif
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($dokumen->file)
                        <div class="mt-10 text-center">
                            <a href="{{ route('view.document', ['id' => $dokumen->id]) }}"
                               class="inline-flex items-center px-8 py-3 text-base font-medium text-white bg-blue-600 rounded-lg shadow-md transition duration-150 ease-in-out hover:bg-blue-700 hover:shadow-lg"
                               target="_blank">
                                <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Lihat Dokumen
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="inline-flex justify-center items-center mb-6 w-20 h-20 bg-red-100 rounded-full">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <h1 class="mb-4 text-3xl font-bold text-gray-900">Verifikasi Gagal</h1>
                    <p class="text-lg text-gray-600">{{ $message ?? 'Dokumen tidak dapat diverifikasi' }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Add Modal -->
    <div id="documentModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center p-4 bg-gray-100">
                <h3 class="text-lg font-semibold">Dokumen Preview</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <iframe id="pdf-viewer"></iframe>
        </div>
    </div>

    <script>
        function showDocument(url) {
            const modal = document.getElementById('documentModal');
            const viewer = document.getElementById('pdf-viewer');

            // Set the source of the iframe
            viewer.src = url;

            // Show the modal
            modal.style.display = 'block';

            // Prevent body scrolling when modal is open
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('documentModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('documentModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Optional: Show a toast/notification that text was copied
                alert('Kode pengesahan berhasil disalin!');
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        }
    </script>
</body>
</html>