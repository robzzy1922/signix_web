@extends('layouts.kemahasiswaan')
@section('title', 'Dashboard Kemahasiswaan')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="container px-4 py-8 mx-auto">
        <div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-2 lg:grid-cols-4">
            <!-- Surat yang diajukan -->
            <a href="{{ route('kemahasiswaan.riwayat', ['status' => 'diajukan']) }}" class="block transition-all transform hover:scale-105">
                <div class="p-6 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl shadow-lg">
                    <div class="flex flex-col space-y-3">
                        <div class="flex justify-between items-center">
                            <div class="flex flex-col items-start">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <div class="p-3 mb-3 bg-yellow-300 bg-opacity-30 rounded-full">
                                    <i class="text-3xl text-white fas fa-file-alt"></i>
                                </div>
                                <h2 class="text-lg font-semibold text-white">Dokumen Diajukan Ormawa</h2>
                            </div>
                            <span class="text-5xl font-bold text-white">{{ $countDiajukan }}</span>
                        </div>
                        <div class="absolute right-2 bottom-2 opacity-10">
                            <i class="text-6xl text-white fas fa-folder-open"></i>
                        </div>
                    </div>
                </div>
            </a>
            <!-- Surat sudah tertanda -->
            <a href="{{ route('kemahasiswaan.riwayat', ['status' => 'disahkan']) }}" class="block transition-all transform hover:scale-105">
                <div class="p-6 bg-gradient-to-br from-green-400 to-green-500 rounded-xl shadow-lg">
                    <div class="flex flex-col space-y-3">
                        <div class="flex justify-between items-center">
                            <div class="flex flex-col items-start">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="p-3 mb-3 bg-green-300 bg-opacity-30 rounded-full">
                                    <i class="text-3xl text-white fas fa-file-signature"></i>
                                </div>
                                <h2 class="text-lg font-semibold text-white">Dokumen Tertanda</h2>
                            </div>
                            <span class="text-5xl font-bold text-white">{{ $countDisahkan }}</span>
                        </div>
                        <div class="absolute right-2 bottom-2 opacity-10">
                            <i class="text-6xl text-white fas fa-check-double"></i>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Surat perlu direvisi -->
            <a href="{{ route('kemahasiswaan.riwayat', ['status' => 'butuh_revisi']) }}" class="block transition-all transform hover:scale-105">
                <div class="p-6 bg-gradient-to-br from-red-400 to-red-500 rounded-xl shadow-lg">
                    <div class="flex flex-col space-y-3">
                        <div class="flex justify-between items-center">
                            <div class="flex flex-col items-start">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div class="p-3 mb-3 bg-red-300 bg-opacity-30 rounded-full">
                                    <i class="text-3xl text-white fas fa-file-medical-alt"></i>
                                </div>
                                <h2 class="text-lg font-semibold text-white">Perlu Direvisi Ormawa</h2>
                            </div>
                            <span class="text-5xl font-bold text-white">{{ $countButuhRevisi }}</span>
                        </div>
                        <div class="absolute right-2 bottom-2 opacity-10">
                            <i class="text-6xl text-white fas fa-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </a>


            <!-- Surat sudah direvisi -->
            <a href="{{ route('kemahasiswaan.riwayat', ['status' => 'direvisi']) }}" class="block transition-all transform hover:scale-105">
                <div class="p-6 bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl shadow-lg">
                    <div class="flex flex-col space-y-3">
                        <div class="flex justify-between items-center">
                            <div class="flex flex-col items-start">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <div class="p-3 mb-3 bg-blue-300 bg-opacity-30 rounded-full">
                                    <i class="text-3xl text-white fas fa-file-code"></i>
                                </div>
                                <h2 class="text-lg font-semibold text-white">Sudah Direvisi Ormawa</h2>
                            </div>
                            <span class="text-5xl font-bold text-white">{{ $countRevisi }}</span>
                        </div>
                        <div class="absolute right-2 bottom-2 opacity-10">
                            <i class="text-6xl text-white fas fa-sync-alt"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Search and Filter Section -->
        <div class="flex flex-col justify-between items-center mb-8 space-y-4 md:flex-row md:space-y-0">
            <div class="w-full md:w-64">
                <form method="GET" action="{{ route('kemahasiswaan.dashboard') }}" class="flex">
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
                <form method="GET" action="{{ route('kemahasiswaan.dashboard') }}">
                    <select name="status" class="px-4 py-2 rounded-lg border" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="disahkan" {{ request('status') == 'disahkan' ? 'selected' : '' }}>Tertanda</option>
                        <option value="butuh revisi" {{ request('status') == 'butuh revisi' ? 'selected' : '' }}>Perlu direvisi</option>
                        <option value="sudah direvisi" {{ request('status') == 'sudah direvisi' ? 'selected' : '' }}>Sudah direvisi</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto p-4 w-full bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">No. Surat</th>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Tanggal Pengajuan</th>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Nama Pengaju</th>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Hal</th>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Dari</th>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if ($dokumens->isEmpty())
                        <tr>
                            <td colspan="6" class="py-8 text-center">
                                <div class="flex flex-col justify-center items-center">
                                    <i class="text-4xl text-gray-400 fas fa-inbox"></i>
                                    <p class="mt-2 text-gray-600">Tidak ada data yang tersedia.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                    @foreach($dokumens as $dokumen)
                        <tr data-id="{{ $dokumen->id }}">
                            <td class="px-6 py-4 whitespace-nowrap" data-nomor>{{ $dokumen->nomor_surat }}</td>
                            <td class="px-6 py-4 whitespace-nowrap" data-tanggal>{{ $dokumen->tanggal_pengajuan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap" data-namaPengirim>{{ $dokumen->ormawa->namaMahasiswa }}</td>
                            <td class="px-6 py-4 whitespace-nowrap" data-perihal>{{ $dokumen->perihal }}</td>
                            <td class="px-6 py-4 whitespace-nowrap" data-ormawa>{{ $dokumen->ormawa->namaOrmawa }}</td>
                            <td class="px-6 py-4 whitespace-nowrap" data-status>
                                @php
                                    $statusClass = match($dokumen->status_dokumen) {
                                        'diajukan' => 'bg-yellow-100 text-yellow-800',
                                        'disahkan' => 'bg-green-100 text-green-800',
                                        'butuh revisi' => 'bg-red-100 text-red-800',
                                        'sudah direvisi' => 'bg-blue-100 text-blue-800',
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
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Detail Dokumen</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-4" id="modalContent">
                    <!-- Content will be loaded here -->
                </div>

                <!-- QR Code Editor Modal Content -->
                <div id="qrCodeEditor" class="hidden">
                    <div class="relative w-full h-[600px] bg-gray-100">
                        <!-- PDF Preview -->
                        <iframe id="pdfFrame" class="w-full h-full"></iframe>

                        <!-- Draggable & Resizable QR Code Container -->
                        <div id="qrCode" class="hidden absolute bg-white rounded-lg shadow-lg cursor-move"
                             style="width: 100px; height: 100px; top: 50px; left: 50px;">
                            <img id="qrImage" src="" alt="QR Code" class="object-contain w-full h-full"/>
                            <!-- Resize handle -->
                            <div class="absolute right-0 bottom-0 w-4 h-4 bg-blue-500 rounded-full opacity-50 cursor-se-resize"></div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4 space-x-2">
                        <button onclick="saveQrPosition()"
                                class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                            Simpan Posisi
                        </button>
                        <button onclick="cancelQrPosition()"
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                            Batal
                        </button>
                    </div>
                </div>

                <div class="flex flex-col justify-end px-6 py-4 space-y-2 border-t border-gray-200 md:flex-row md:space-y-0 md:space-x-3" id="modalButtons">
                    <button onclick="downloadDocument()"
                            class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Download
                    </button>
                    <button onclick="viewDocument()"
                            class="px-4 py-2 text-white bg-yellow-500 rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                        Lihat
                    </button>
                    <button onclick="showRevisiForm()"
                            class="px-4 py-2 text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Revisi
                    </button>
                    <button onclick="closeModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Tutup
                    </button>
                    <button onclick="editQrCode()"
                            class="px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Bubuhkan QR Code
                    </button>
                    <button id="shareButton" onclick="shareToWhatsApp()" style="display:none;"
                            class="px-4 py-2 text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <svg class="inline-block mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.498 14.382c-.301-.15-1.767-.867-2.04-.966-.273-.101-.473-.15-.673.15-.197.295-.771.964-.944 1.162-.175.195-.349.21-.646.075-.3-.15-1.263-.465-2.403-1.485-.888-.795-1.484-1.77-1.66-2.07-.174-.3-.019-.465.13-.615.136-.135.301-.345.451-.523.146-.181.194-.301.297-.496.1-.21.049-.375-.025-.524-.075-.15-.672-1.62-.922-2.206-.24-.584-.487-.51-.672-.51-.172-.015-.371-.015-.571-.015-.2 0-.523.074-.797.359-.273.3-1.045 1.02-1.045 2.475s1.07 2.865 1.219 3.075c.149.195 2.105 3.195 5.1 4.485.714.3 1.27.48 1.704.629.714.227 1.365.195 1.88.121.574-.091 1.767-.721 2.016-1.426.255-.705.255-1.29.18-1.425-.074-.135-.27-.21-.57-.345m-5.446 7.443h-.016c-1.77 0-3.524-.48-5.055-1.38l-.36-.214-3.75.975 1.005-3.645-.239-.375c-.99-1.576-1.516-3.391-1.516-5.26 0-5.445 4.455-9.885 9.942-9.885 2.654 0 5.145 1.035 7.021 2.91 1.875 1.859 2.909 4.35 2.909 6.99-.004 5.444-4.46 9.885-9.935 9.885M20.52 3.449C18.24 1.245 15.24 0 12.045 0 5.463 0 .104 5.334.101 11.893c0 2.096.549 4.14 1.595 5.945L0 24l6.335-1.652c1.746.943 3.71 1.444 5.71 1.447h.006c6.585 0 11.946-5.336 11.949-11.896 0-3.176-1.24-6.165-3.495-8.411"/>
                        </svg>
                        Bagikan via WhatsApp
                    </button>
                </div>

                <!-- Form Revisi -->
                <div id="revisiForm" class="hidden p-6">
                    <form id="formRevisi" class="space-y-4">
                        @csrf
                        <div>
                            <label for="keteranganRevisi" class="block text-sm font-medium text-gray-700">
                                Keterangan Revisi
                            </label>
                            <textarea
                                id="keteranganRevisi"
                                name="keterangan"
                                rows="4"
                                class="block mt-1 w-full rounded-md border-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required
                            ></textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button
                                type="button"
                                onclick="cancelRevisi()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                            >
                                Batal
                            </button>
                            <button
                                type="button"
                                onclick="submitRevisi()"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700"
                            >
                                Kirim Revisi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/interact.js/1.10.11/interact.min.js"></script>
    <script>
    let currentDocumentId = null;
    let currentFileUrl = null;

    function showModal(documentId, pdfUrl) {
        currentDocumentId = documentId;
        currentFileUrl = pdfUrl;

        // Fetch document details
        fetch(`/kemahasiswaan/dokumen/${documentId}`)
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
                        ${data.keterangan_revisi ? `
                        <div>
                            <p class="text-sm font-medium text-gray-500">Keterangan Revisi</p>
                            <p class="mt-1 text-red-600">${data.keterangan_revisi}</p>
                        </div>
                        ` : ''}
                        ${data.keterangan_pengirim && data.status_dokumen.toLowerCase() === 'sudah direvisi' ? `
                        <div>
                            <p class="text-sm font-medium text-gray-500">Keterangan Dari Ormawa</p>
                            <p class="mt-1 text-blue-600">${data.keterangan_pengirim}</p>
                        </div>
                        ` : ''}
                    </div>
                `;

                // Show the share button only if document is 'disahkan'
                const shareButton = document.getElementById('shareButton');
                if (data.status_dokumen.toLowerCase() === 'disahkan') {
                    shareButton.style.display = 'inline-flex';
                } else {
                    shareButton.style.display = 'none';
                }
            });

        document.getElementById('documentModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('documentModal').classList.add('hidden');
        document.getElementById('qrCodeEditor').classList.add('hidden');
        document.getElementById('modalContent').classList.remove('hidden');
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

    function generateQrCode(documentId) {
        if (!documentId) return;

        const button = document.querySelector(`button[onclick="generateQrCode(${documentId})"]`);
        const originalText = button.innerHTML;
        button.innerHTML = 'Generating...';
        button.disabled = true;

        fetch(`/kemahasiswaan/dokumen/${documentId}/generate-qr`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modalContent').classList.add('hidden');
                document.getElementById('qrCodeEditor').classList.remove('hidden');

                document.getElementById('pdfFrame').src = currentFileUrl;

                const qrCode = document.getElementById('qrCode');
                const qrImage = document.getElementById('qrImage');

                qrImage.onload = function() {
                    qrCode.classList.remove('hidden');
                    initializeInteract();
                };

                qrImage.src = data.qrCodeUrl;

                qrCode.style.transform = 'translate(50px, 50px)';
                qrCode.setAttribute('data-x', 50);
                qrCode.setAttribute('data-y', 50);
                qrCode.style.width = '100px';
                qrCode.style.height = '100px';
            } else {
                alert(data.message || 'Failed to generate QR Code');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error generating QR Code');
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }

    function initializeInteract() {
        interact('#qrCode')
            .draggable({
                inertia: true,
                modifiers: [
                    interact.modifiers.restrictRect({
                        restriction: 'parent',
                        endOnly: true
                    })
                ],
                autoScroll: true,
                listeners: {
                    move: dragMoveListener
                }
            })
            .resizable({
                edges: { left: true, right: true, bottom: true, top: true },
                restrictEdges: {
                    outer: 'parent',
                    endOnly: true,
                },
                restrictSize: {
                    min: { width: 30, height: 30 },
                    max: { width: 200, height: 200 },
                },
                inertia: true,
                listeners: {
                    move: resizeMoveListener
                }
            });
    }

    function dragMoveListener(event) {
        const target = event.target;
        const x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
        const y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

        target.style.transform = `translate(${x}px, ${y}px)`;
        target.setAttribute('data-x', x);
        target.setAttribute('data-y', y);
    }

    function resizeMoveListener(event) {
        const target = event.target;
        let x = (parseFloat(target.getAttribute('data-x')) || 0);
        let y = (parseFloat(target.getAttribute('data-y')) || 0);

        // Update element width/height
        target.style.width = `${event.rect.width}px`;
        target.style.height = `${event.rect.height}px`;

        // Translate when resizing from top or left edges
        x += event.deltaRect.left;
        y += event.deltaRect.top;

        target.style.transform = `translate(${x}px, ${y}px)`;
        target.setAttribute('data-x', x);
        target.setAttribute('data-y', y);
    }

    function saveQrPosition() {
        const qrElement = document.getElementById('qrCode');
        const rect = qrElement.getBoundingClientRect();
        const containerRect = document.getElementById('pdfFrame').getBoundingClientRect();

        // Calculate relative position
        const position = {
            x: (parseFloat(qrElement.getAttribute('data-x')) || 0),
            y: (parseFloat(qrElement.getAttribute('data-y')) || 0),
            width: parseFloat(qrElement.style.width),
            height: parseFloat(qrElement.style.height)
        };

        fetch(`/kemahasiswaan/dokumen/${currentDocumentId}/save-qr-position`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(position)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Posisi QR code berhasil disimpan');
                location.reload();
            } else {
                alert(data.message || 'Gagal menyimpan posisi QR code');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menyimpan posisi QR code');
        });
    }

    function cancelQrPosition() {
        document.getElementById('qrCodeEditor').classList.add('hidden');
        document.getElementById('modalContent').classList.remove('hidden');
    }

    function editQrCode() {
        if (!currentDocumentId) return;

        // Generate QR Code first
        fetch(`/kemahasiswaan/dokumen/${currentDocumentId}/generate-qr`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to QR editor page
                window.location.href = `/kemahasiswaan/dokumen/${currentDocumentId}/edit-qr`;
            } else {
                alert(data.message || 'Gagal membuat QR Code');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error generating QR Code');
        });
    }

    function showRevisiForm() {
        document.getElementById('modalContent').classList.add('hidden');
        document.getElementById('revisiForm').classList.remove('hidden');
        document.getElementById('modalButtons').classList.add('hidden');
    }

    function cancelRevisi() {
        document.getElementById('revisiForm').classList.add('hidden');
        document.getElementById('modalContent').classList.remove('hidden');
        document.getElementById('modalButtons').classList.remove('hidden');
        document.getElementById('formRevisi').reset();
    }

    function submitRevisi() {
        const keterangan = document.getElementById('keteranganRevisi').value;

        if (!keterangan.trim()) {
            alert('Keterangan revisi tidak boleh kosong');
            return;
        }

        // Debug: Log URL dan data yang akan dikirim
        console.log('Submitting to:', `/kemahasiswaan/dokumen/${currentDocumentId}/revisi`);
        console.log('Data:', { keterangan: keterangan });

        fetch(`/kemahasiswaan/dokumen/${currentDocumentId}/revisi`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                keterangan: keterangan
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Dokumen berhasil direvisi');
                window.location.reload();
            } else {
                throw new Error(data.message || 'Gagal melakukan revisi');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat melakukan revisi: ' + error.message);
        });
    }

    // Function to share document via WhatsApp
    function shareToWhatsApp() {
        if (!currentDocumentId) return;

        // Get the current document details from the modal
        const perihalElement = document.getElementById('modalContent').querySelector('.mt-1:nth-of-type(3)');
        const nomorElement = document.getElementById('modalContent').querySelector('.mt-1:nth-of-type(1)');
        const tanggalElement = document.getElementById('modalContent').querySelector('.mt-1:nth-of-type(2)');

        // Extract text content from elements
        const documentTitle = perihalElement ? perihalElement.textContent.trim() : 'Dokumen';
        const documentNumber = nomorElement ? nomorElement.textContent.trim() : '';
        const documentDate = tanggalElement ? tanggalElement.textContent.trim() : '';

        // Generate the document link
        const documentLink = window.location.origin + `/kemahasiswaan/dokumen/${currentDocumentId}/view`;

        // Create a more detailed message text
        const messageText = `*INFORMASI DOKUMEN RESMI*\n\n` +
            `Dokumen dengan detail berikut telah disahkan:\n\n` +
            `📄 *Perihal:* ${documentTitle}\n` +
            `📝 *Nomor Surat:* ${documentNumber}\n` +
            `📅 *Tanggal Pengajuan:* ${documentDate}\n\n` +
            `Status dokumen: *DISAHKAN*\n\n` +
            `Silahkan akses dokumen melalui tautan berikut:\n${documentLink}\n\n` +
            `Pesan ini dikirim melalui Sistem Manajemen Dokumen SigniX.`;

        // Show dialog with message customization
        Swal.fire({
            title: 'Bagikan ke WhatsApp',
            html: `
                <div class="text-left">
                    <p class="mb-3">Dokumen akan dibagikan langsung via WhatsApp tanpa perlu diunduh terlebih dahulu.</p>
                    <p class="mb-2">Teks pesan yang akan dibagikan:</p>
                    <textarea id="whatsappMessage" class="p-2 w-full rounded border" rows="10" style="font-size: 14px;">${messageText}</textarea>
                </div>
            `,
            confirmButtonText: 'Bagikan ke WhatsApp',
            confirmButtonColor: '#25D366',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            icon: 'info'
        }).then((result) => {
            if (result.isConfirmed) {
                // Get the message and open WhatsApp
                const message = document.getElementById('whatsappMessage').value;
                const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
                window.open(whatsappUrl, '_blank');
            }
        });
    }

    // Close modal when clicking outside
    document.getElementById('documentModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    </script>
@endsection

