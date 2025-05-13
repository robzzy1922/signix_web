<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengesahan Dokumen - Signix</title>

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
<body class="bg-gray-50">
    <div class="py-8 min-h-screen">
        <div class="container px-4 mx-auto">
            <div class="overflow-hidden mx-auto max-w-4xl bg-white rounded-2xl border border-gray-200 shadow-sm">
                <div class="p-8">
                    <!-- Header dengan Logo -->
                    <div class="flex gap-4 items-center mb-6">
                        <img src="{{ asset('images/logo_signix.png') }}" alt="Signix Logo" class="w-12 h-12">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Detail Pengesahan Dokumen</h1>
                            <p class="text-gray-600">Signix - Sistem Pengesahan Dokumen Digital</p>
                        </div>
                    </div>

                    @if(isset($verified) && $verified)
                        <!-- Status Verifikasi -->
                        <div class="p-4 mb-8 bg-green-50 rounded-lg border border-green-200">
                            <div class="flex gap-3 items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-green-800">Pengajuan Terverifikasi</p>
                                    <p class="text-sm text-green-700">Dokumen ini telah diverifikasi dan disahkan</p>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Detail -->
                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Nama Pengaju</p>
                                    <p class="font-medium text-gray-900">{{ $dokumen->ormawa->namaMahasiswa?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Nomor Surat</p>
                                    <p class="font-medium text-gray-900">{{ $dokumen->nomor_surat ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Kode Pengesahan</p>
                                    <p class="font-medium text-gray-900">{{ $dokumen->kode_pengesahan ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Perihal</p>
                                    <p class="font-medium text-gray-900">{{ $dokumen->perihal ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                                    <p class="font-medium text-gray-900">{{ $dokumen->tanggal_pengajuan ? \Carbon\Carbon::parse($dokumen->tanggal_pengajuan)->format('d M Y') : '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Tanggal Disahkan</p>
                                    <p class="font-medium text-gray-900">{{ $dokumen->tanggal_verifikasi ? \Carbon\Carbon::parse($dokumen->tanggal_verifikasi)->format('d M Y, H:i') : '-' }}</p>
                                </div>
                            </div>

                            <div class="pt-4">
                                <p class="text-sm text-gray-600">Status</p>
                                <div class="mt-1">
                                    <span class="inline-flex px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">
                                        {{ ucfirst($dokumen->status_dokumen) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Pengesahan -->
                            @if($dokumen->dosen || $dokumen->kemahasiswaan)
                                <div class="pt-4">
                                    <p class="mb-2 text-sm text-gray-600">Ditandatangani oleh</p>
                                    <div class="space-y-3">
                                        @if($dokumen->dosen)
                                            <div class="flex gap-2 items-center text-gray-900">
                                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Dosen: {{ $dokumen->dosen->nama_dosen }}</span>
                                            </div>
                                        @endif
                                        @if($dokumen->kemahasiswaan)
                                            <div class="flex gap-2 items-center text-gray-900">
                                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Kemahasiswaan: {{ $dokumen->kemahasiswaan->nama_kemahasiswaan }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Tombol Lihat Dokumen -->
                        @if($dokumen->file)
                            <div class="flex justify-center mt-8">
                                <a href="{{ route('view.document', ['id' => $dokumen->id]) }}"
                                   class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                   target="_blank">
                                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Lihat Dokumen
                                </a>
                            </div>
                        @endif

                    @else
                        <!-- Tampilan untuk dokumen tidak terverifikasi -->
                        <div class="p-8 text-center">
                            <div class="inline-flex justify-center items-center mb-6 w-16 h-16 bg-red-100 rounded-full">
                                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Verifikasi Gagal</h2>
                            <p class="mt-2 text-gray-600">{{ $message ?? 'Dokumen tidak dapat diverifikasi' }}</p>
                        </div>
                    @endif
                </div>
            </div>
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