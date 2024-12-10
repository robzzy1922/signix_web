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
    <div class="min-h-screen bg-gray-50 py-16">
        <div class="container px-4 mx-auto">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
                @if(isset($verified) && $verified)
                    <div class="p-8">
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-6">
                                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-3">Dokumen Terverifikasi</h1>
                            <p class="text-gray-600">
                                Diverifikasi pada: 
                                @if($dokumen->tanggal_verifikasi)
                                    {{ \Carbon\Carbon::parse($dokumen->tanggal_verifikasi)->setTimezone('Asia/Jakarta')->format('d M Y H:i:s') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                    <div class="mt-10 border-t border-gray-200 pt-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-8">Detail Dokumen</h2>
                        <div class="space-y-6">
                            <div class="flex items-center">
                                <span class="w-1/3 text-gray-600">Kode Pengesahan:</span>
                                <div class="w-2/3 flex items-center space-x-2">
                                    <code class="flex-1 font-mono text-sm bg-gray-50 px-4 py-2 rounded-lg border border-gray-200 tracking-wider">
                                        {{ $dokumen->kode_pengesahan }}
                                    </code>
                                    <button onclick="copyToClipboard('{{ $dokumen->kode_pengesahan }}')" 
                                            class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <span class="w-1/3 text-gray-600">Nomor Surat:</span>
                                <span class="w-2/3 font-medium text-gray-900 px-4 py-2">{{ $dokumen->nomor_surat }}</span>
                            </div>
                            
                            <div class="flex items-center">
                                <span class="w-1/3 text-gray-600">Tanggal Pengajuan:</span>
                                <span class="w-2/3 font-medium text-gray-900 px-4 py-2">{{ $dokumen->tanggal_pengajuan }}</span>
                            </div>
                            
                            <div class="flex items-center">
                                <span class="w-1/3 text-gray-600">Perihal:</span>
                                <span class="w-2/3 font-medium text-gray-900 px-4 py-2">{{ $dokumen->perihal }}</span>
                            </div>
                            
                            <div class="flex items-center">
                                <span class="w-1/3 text-gray-600">Status:</span>
                                <span class="w-2/3 px-4 py-2">
                                    <span class="inline-flex px-4 py-1.5 text-sm font-medium text-green-700 bg-green-100 rounded-full">
                                        {{ ucfirst($dokumen->status_dokumen) }}
                                    </span>
                                </span>
                            </div>
                            
                            @if($dokumen->dosen)
                                <div class="flex items-center">
                                    <span class="w-1/3 text-gray-600">Disahkan oleh:</span>
                                    <span class="w-2/3 font-medium text-gray-900 px-4 py-2">{{ $dokumen->dosen->nama_dosen }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($dokumen->file)
                        <div class="mt-10 text-center">
                            <a href="{{ route('view.document', ['id' => $dokumen->id]) }}"
                               class="inline-flex items-center px-8 py-3 text-base font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition duration-150 ease-in-out shadow-md hover:shadow-lg"
                               target="_blank">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Lihat Dokumen
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-6">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Verifikasi Gagal</h1>
                    <p class="text-gray-600 text-lg">{{ $message ?? 'Dokumen tidak dapat diverifikasi' }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Add Modal -->
    <div id="documentModal" class="modal">
        <div class="modal-content">
            <div class="bg-gray-100 p-4 flex justify-between items-center">
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