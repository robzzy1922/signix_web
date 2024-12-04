@extends('layouts.dosen')
@section('title', 'Edit QR Code Position')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="relative w-full h-[600px] bg-gray-100">
        <!-- PDF Preview -->
        <iframe id="pdfFrame" class="w-full h-full" src="{{ asset('storage/' . $dokumen->file) }}"></iframe>

        <!-- Draggable & Resizable QR Code Container -->
        <div id="qrCode" class="absolute bg-white rounded-lg shadow-lg cursor-move"
             style="width: 100px; height: 100px; top: 50px; left: 50px;">
            <img id="qrImage" src="{{ asset('storage/' . $dokumen->qr_code_path) }}" alt="QR Code" class="object-contain w-full h-full"/>
            <!-- Resize handle -->
            <div class="absolute right-0 bottom-0 w-4 h-4 bg-blue-500 rounded-full opacity-50 cursor-se-resize"></div>
        </div>
    </div>

    <div class="flex justify-end mt-4 space-x-2">
        <button onclick="saveQrPosition()"
                class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
            Simpan Posisi
        </button>
        <a href="{{ route('dosen.dashboard') }}"
           class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
            Batal
        </a>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/interact.js/1.10.11/interact.min.js"></script>
<script>
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
                    min: { width: 50, height: 50 },
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

        target.style.width = `${event.rect.width}px`;
        target.style.height = `${event.rect.height}px`;

        x += event.deltaRect.left;
        y += event.deltaRect.top;

        target.style.transform = `translate(${x}px, ${y}px)`;
        target.setAttribute('data-x', x);
        target.setAttribute('data-y', y);
    }

    function saveQrPosition() {
        const qrElement = document.getElementById('qrCode');
        const position = {
            x: (parseFloat(qrElement.getAttribute('data-x')) || 0),
            y: (parseFloat(qrElement.getAttribute('data-y')) || 0),
            width: parseFloat(qrElement.style.width),
            height: parseFloat(qrElement.style.height)
        };

        fetch(`{{ route('dosen.dokumen.saveQrPosition', $dokumen->id) }}`, {
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
                window.location.href = '{{ route('dosen.dashboard') }}';
            } else {
                alert(data.message || 'Gagal menyimpan posisi QR code');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menyimpan posisi QR code');
        });
    }

    document.addEventListener('DOMContentLoaded', initializeInteract);
</script>
@endsection
