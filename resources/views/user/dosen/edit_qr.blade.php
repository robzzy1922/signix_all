@extends('layouts.dosen')
@section('title', 'Edit QR Code Position')
@section('content')

<!-- Tambahkan CSS untuk container -->
<style>
    #pdfContainer {
        background-color: #525659;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        height: 100%;
        overflow: hidden; /* Prevent content from overflowing */
    }

    #pdfViewer {
        background-color: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 90%; /* Adjusted to 90% for more space */
        width: auto;
        height: auto;
        max-height: calc(100vh - 200px); /* Reduced padding for more height */
    }

    #qrCode {
        position: absolute;
        z-index: 1000;
        background: white;
        border: 1px solid #ccc;
        cursor: move;
    }

    .page-controls {
        margin-top: 1rem;
        display: flex;
        gap: 1rem;
        align-items: center;
        background: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
    }

    .page-controls button {
        padding: 0.5rem 1rem;
        background: #4B5563;
        color: white;
        border-radius: 0.375rem;
        border: none;
        cursor: pointer;
    }

    .page-controls button:hover {
        background: #374151;
    }

    .page-controls button:disabled {
        background: #9CA3AF;
        cursor: not-allowed;
    }
</style>

<div class="container mx-auto px-4 py-8 max-w-6xl">
    @if(isset($dokumen))
        <!-- Breadcrumb -->
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dosen.dashboard') }}" class="inline-flex items-center text-gray-700 hover:text-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li class="inline-flex items-center">
                    <span class="mx-2 text-gray-400">/</span>
                    <span class="text-gray-500">Edit QR Code</span>
                </li>
            </ol>
        </nav>

        <!-- Container PDF dan QR -->
        <div id="pdfContainer" class="relative w-full">
            <!-- PDF Viewer -->
            <canvas id="pdfViewer" class="w-full h-full"></canvas>

            <!-- Kontrol Halaman -->
            <div class="page-controls">
                <button id="prevPage" disabled>Previous</button>
                <span id="pageInfo">Page: <span id="pageNum">1</span> / <span id="pageCount">1</span></span>
                <button id="nextPage">Next</button>
            </div>

            <!-- QR Code Draggable -->
            <div id="qrCode" class="absolute bg-white rounded-lg shadow-lg"
                 style="width: 100px; height: 100px; top: 50px; left: 50px;">
                <img id="qrImage"
                     src="{{ asset('storage/' . $dokumen->qr_code_path) }}"
                     alt="QR Code"
                     class="w-full h-full object-contain"/>
                <!-- Resize handle -->
                <div class="absolute right-0 bottom-0 w-4 h-4 bg-blue-500 rounded-full opacity-50 cursor-se-resize" id="resizeHandle"></div>

                <!-- Move control button -->
                <div class="absolute -bottom-12 left-1/2 transform -translate-x-1/2">
                    <button id="moveControl" class="p-2 bg-gray-200 rounded-full hover:bg-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M12 3v18m-6-6l6 6 6-6m-12-6l6-6 6 6" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tombol aksi -->
        <div class="flex justify-end mt-4 space-x-2">
            <button onclick="saveQrPosition({{ $dokumen->id }})"
                    class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                Simpan Posisi
            </button>
            <a href="{{ route('dosen.dashboard') }}"
               class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                Batal
            </a>
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-red-500">Dokumen tidak ditemukan.</p>
            <a href="{{ route('dosen.dashboard') }}" class="mt-4 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Kembali ke Dashboard
            </a>
        </div>
    @endif
</div>

<!-- Tambahkan PDF.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
<script>
    let pdfDoc = null;
    let pageNum = 1;
    let pageRendering = false;
    let pageNumPending = null;

    // Konfigurasi PDF.js
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js';

    async function renderPage(num) {
        pageRendering = true;

        try {
            const page = await pdfDoc.getPage(num);
            const canvas = document.getElementById('pdfViewer');
            const context = canvas.getContext('2d');

            // Calculate scale based on container width
            const containerWidth = canvas.parentElement.clientWidth;
            const viewport = page.getViewport({ scale: 1 });

            // Increase scale for better visibility
            const scale = Math.min(
                (containerWidth - 20) / viewport.width, // Reduced padding
                (window.innerHeight - 200) / viewport.height // Reduced height limit
            ) * 1.2; // Increase scale factor

            const scaledViewport = page.getViewport({ scale });

            canvas.width = scaledViewport.width;
            canvas.height = scaledViewport.height;

            const renderContext = {
                canvasContext: context,
                viewport: scaledViewport
            };

            await page.render(renderContext).promise;
            pageRendering = false;

            if (pageNumPending !== null) {
                renderPage(pageNumPending);
                pageNumPending = null;
            }

            // Update page controls
            document.getElementById('pageNum').textContent = num;
            document.getElementById('prevPage').disabled = num <= 1;
            document.getElementById('nextPage').disabled = num >= pdfDoc.numPages;
        } catch (error) {
            console.error('Error rendering page:', error);
            pageRendering = false;
        }
    }

    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }

    function onPrevPage() {
        if (pageNum <= 1) return;
        pageNum--;
        queueRenderPage(pageNum);
    }

    function onNextPage() {
        if (pageNum >= pdfDoc.numPages) return;
        pageNum++;
        queueRenderPage(pageNum);
    }

    // Inisialisasi PDF
    async function initPDF() {
        try {
            const url = "{{ asset('storage/' . $dokumen->file) }}";
            pdfDoc = await pdfjsLib.getDocument(url).promise;
            document.getElementById('pageCount').textContent = pdfDoc.numPages;

            // Render halaman pertama
            renderPage(pageNum);

            // Setup event listeners
            document.getElementById('prevPage').addEventListener('click', onPrevPage);
            document.getElementById('nextPage').addEventListener('click', onNextPage);
        } catch (error) {
            console.error('Error loading PDF:', error);
        }
    }

    // Inisialisasi saat dokumen dimuat
    document.addEventListener('DOMContentLoaded', function() {
        initPDF();
        initializeInteract();
    });

    // Update fungsi initializeInteract
    function initializeInteract() {
        interact('#qrCode')
            .draggable({
                enabled: false,
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
                edges: { right: true, bottom: true },
                listeners: {
                    move: function (event) {
                        let { x, y } = event.target.dataset;

                        x = (parseFloat(x) || 0);
                        y = (parseFloat(y) || 0);

                        Object.assign(event.target.style, {
                            width: `${event.rect.width}px`,
                            height: `${event.rect.height}px`,
                            transform: `translate(${x}px, ${y}px)`
                        });
                    }
                },
                modifiers: [
                    interact.modifiers.restrictSize({
                        min: { width: 30, height: 30 },
                        max: { width: 150, height: 150 }
                    })
                ],
                inertia: true
            });
    }

    // Fungsi-fungsi lain tetap sama
    function dragMoveListener(event) {
        const target = event.target;
        const x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
        const y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

        target.style.transform = `translate(${x}px, ${y}px)`;
        target.setAttribute('data-x', x);
        target.setAttribute('data-y', y);
    }

    // Fungsi untuk menghitung posisi relatif
    function calculateRelativePosition(element, container) {
        const elementRect = element.getBoundingClientRect();
        const containerRect = container.getBoundingClientRect();

        // Hitung posisi relatif dalam persentase
        const x = ((elementRect.left - containerRect.left) / containerRect.width) * 100;
        const y = ((elementRect.top - containerRect.top) / containerRect.height) * 100;

        // Hitung ukuran relatif dalam persentase
        const width = (elementRect.width / containerRect.width) * 100;
        const height = (elementRect.height / containerRect.height) * 100;

        return {
            x: x,
            y: y,
            width: width,
            height: height,
            page: pageNum
        };
    }

    // Update fungsi saveQrPosition
    function saveQrPosition(dokumenId) {
        const qrElement = document.getElementById('qrCode');
        const container = document.getElementById('pdfViewer');
        const position = calculateRelativePosition(qrElement, container);

        // Log position data for debugging
        console.log('Saving position:', position);

        fetch(`/dosen/dokumen/${dokumenId}/save-qr-position`, {
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
                window.location.href = '{{ route("dosen.dashboard") }}';
            } else {
                alert(data.message || 'Gagal menyimpan posisi QR code');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menyimpan posisi QR code');
        });
    }

    // Update event listeners for move control
    document.addEventListener('DOMContentLoaded', function() {
        const moveControl = document.getElementById('moveControl');
        let isDragging = false;
        let startX, startY;
        let qrElement = document.getElementById('qrCode');
        let originalWidth, originalHeight;

        moveControl.addEventListener('mousedown', function(e) {
            isDragging = true;
            startX = e.clientX;
            startY = e.clientY;
            // Store original dimensions
            originalWidth = qrElement.offsetWidth;
            originalHeight = qrElement.offsetHeight;
            e.preventDefault();
        });

        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;

            const dx = e.clientX - startX;
            const dy = e.clientY - startY;

            const currentX = parseFloat(qrElement.getAttribute('data-x')) || 0;
            const currentY = parseFloat(qrElement.getAttribute('data-y')) || 0;

            const newX = currentX + dx;
            const newY = currentY + dy;

            // Maintain original dimensions while moving
            qrElement.style.width = `${originalWidth}px`;
            qrElement.style.height = `${originalHeight}px`;
            qrElement.style.transform = `translate(${newX}px, ${newY}px)`;
            qrElement.setAttribute('data-x', newX);
            qrElement.setAttribute('data-y', newY);

            startX = e.clientX;
            startY = e.clientY;
        });

        document.addEventListener('mouseup', function() {
            isDragging = false;
        });
    });
</script>
@endsection
