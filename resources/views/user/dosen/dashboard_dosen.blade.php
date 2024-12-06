@extends('layouts.dosen')
@section('title', 'Dashboard Dosen')
@section('content')
    <div class="container px-4 py-8 mx-auto">
        <div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-3">
            <!-- Surat yang diajukan -->
            <a href="{{ route('dosen.riwayat', ['status' => 'diajukan']) }}" class="block">
                <div class="p-4 bg-yellow-400 rounded-lg shadow transition-colors hover:bg-yellow-500">
                    <div class="flex items-center">
                        <i class="mr-2 text-2xl fas fa-envelope"></i>
                        <h2 class="text-lg font-bold">{{ $countDiajukan }} Surat diajukan</h2>
                    </div>
                </div>
            </a>

            <!-- Surat sudah tertanda -->
            <a href="{{ route('dosen.riwayat', ['status' => 'disahkan']) }}" class="block">
                <div class="p-4 bg-green-400 rounded-lg shadow transition-colors hover:bg-green-500">
                    <div class="flex items-center">
                        <i class="mr-2 text-2xl fas fa-check-circle"></i>
                        <h2 class="text-lg font-bold">{{ $countDisahkan }} Surat sudah tertanda</h2>
                    </div>
                </div>
            </a>

            <!-- Surat perlu direvisi -->
            <a href="{{ route('dosen.riwayat', ['status' => 'direvisi']) }}" class="block">
                <div class="p-4 bg-blue-400 rounded-lg shadow transition-colors hover:bg-blue-500">
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
                            <td class="px-6 py-4 whitespace-nowrap" data-perihal>{{ $dokumen->perihal }}</td>
                            <td class="px-6 py-4 whitespace-nowrap" data-ormawa>{{ $dokumen->ormawa->namaOrmawa }}</td>
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

                <style>
                    .resize-drag {
                        background-color: #29e;
                        color: white;
                        font-size: 20px;
                        font-family: sans-serif;
                        border-radius: 8px;
                        padding: 20px;
                        margin: 30px 20px;
                        touch-action: none;
                        user-select: none;
                        position: absolute;
                    }

                    .resize-container {
                        position: relative;
                        width: 100%;
                        height: 400px;
                        border: 1px solid #ccc;
                        overflow: hidden;
                    }
                </style>

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
                    <button onclick="editQrCode()"
                            class="px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Bubuhkan QR Code
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div id="notification" class="hidden fixed top-4 right-4 z-50 max-w-md">
        <div class="rounded-lg shadow-lg">
            <div id="notificationContent" class="flex items-center p-4 text-white">
                <svg class="mr-2 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span id="notificationMessage"></span>
                <button onclick="hideNotification()" class="ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
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
                document.getElementById('documentModal').classList.remove('hidden');
            });
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

    function generateQrCode() {
        if (!currentDocumentId) return;

        fetch(`/dosen/dokumen/${currentDocumentId}/generate-qr`, {
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
                document.getElementById('qrImage').src = data.qrCodeUrl;
                document.getElementById('qrCode').classList.remove('hidden');
                initializeInteract();
                showNotification('QR Code berhasil dibuat', 'success');
            } else {
                showNotification(data.message || 'Gagal generate QR Code', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error generating QR Code', 'error');
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
        const position = {
            x: (parseFloat(qrElement.getAttribute('data-x')) || 0),
            y: (parseFloat(qrElement.getAttribute('data-y')) || 0),
            width: parseFloat(qrElement.style.width),
            height: parseFloat(qrElement.style.height)
        };

        fetch(`/dosen/dokumen/${currentDocumentId}/save-qr-position`, {
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
                showNotification('Posisi QR code berhasil disimpan', 'success');
                setTimeout(() => location.reload(), 2000);
            } else {
                showNotification(data.message || 'Gagal menyimpan posisi QR code', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Gagal menyimpan posisi QR code', 'error');
        });
    }

    function cancelQrPosition() {
        document.getElementById('qrCodeEditor').classList.add('hidden');
        document.getElementById('modalContent').classList.remove('hidden');
    }

    function editQrCode() {
        generateQrCode(); // Panggil fungsi generateQrCode langsung
        // Pastikan iframe PDF di-update dengan URL yang benar
        document.getElementById('pdfFrame').src = currentFileUrl;
    }

    // Close modal when clicking outside
    document.getElementById('documentModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Notification functions
    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        const content = document.getElementById('notificationContent');
        const messageElement = document.getElementById('notificationMessage');

        // Set colors based on type
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500'
        };

        // Remove any existing color classes
        content.className = 'flex items-center p-4 text-white ' + colors[type];
        messageElement.textContent = message;
        notification.classList.remove('hidden');

        // Auto-hide after 5 seconds
        setTimeout(hideNotification, 5000);
    }

    function hideNotification() {
        document.getElementById('notification').classList.add('hidden');
    }
    </script>
@endsection
