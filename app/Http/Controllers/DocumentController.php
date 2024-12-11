<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TandaQr;
use App\Models\Dokumen;
use Illuminate\Http\Response;

class DocumentController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|mimes:pdf|max:10240',
        ]);

        $filePath = $request->file('document')->store('documents');

        $document = Dokumen::create([
            'user_id' => auth()->id(),
            'file_path' => $filePath,
            'status' => 'pending',
        ]);

        return redirect()->route('documents.show', $document);
    }

    public function show(Dokumen $document)
    {
        return view('user.dosen.show', compact('document'));
    }

    public function saveBarcodePosition(Request $request, Dokumen $document)
    {
        // Validasi posisi
        $request->validate([
            'x' => 'required|numeric',
            'y' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
        ]);

        // Simpan posisi QR code
        $document->update([
            'qr_position_x' => $request->x,
            'qr_position_y' => $request->y,
            'qr_width' => $request->width,
            'qr_height' => $request->height,
        ]);

        // Generate PDF baru dengan QR code
        $pdf = PDF::loadFile(storage_path('app/' . $document->file_path));

        // Tambahkan QR code ke PDF
        $qrCodePath = storage_path('app/' . $document->qr_code_path);
        $pdf->getCanvas()->addImage($qrCodePath,
            $request->x,
            $request->y,
            $request->x + $request->width,
            $request->y + $request->height
        );

        // Simpan PDF baru
        $newPdfPath = 'documents/signed_' . $document->id . '.pdf';
        Storage::put($newPdfPath, $pdf->output());

        // Update dokumen dengan file baru dan status
        $document->update([
            'file_path' => $newPdfPath,
            'status_dokumen' => 'disahkan'
        ]);

        return response()->json(['success' => true]);
    }

    public function insertBarcodeToPdf(Dokumen $document)
    {
        $pdf = Pdf::loadFile(storage_path('app/' . $document->file_path));

        // Posisikan barcode di PDF sesuai dengan data yang disimpan
    // Gunakan PDF library seperti DomPDF untuk sisipkan barcode ke dalam file PDF.

        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isPhpEnabled', true);

    // Generate PDF yang telah disertakan barcode
    $output = $pdf->output();

        // Simpan hasilnya atau kembalikan kepada pengguna
        file_put_contents(storage_path('app/documents/signed_' . $document->id . '.pdf'), $output);
    }

    public function generateQrCode(Dokumen $document)
    {
        try {
            // Generate unique identifier for QR
            $qrData = [
                'document_id' => $document->id,
                'timestamp' => now()->timestamp,
                'validator' => auth()->id()
            ];

            $qrString = json_encode($qrData);

            // Generate QR code using Simple QR Code
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                ->size(300)
                ->errorCorrection('H')
                ->generate($qrString);

            // Save QR code to storage
            $qrPath = 'qrcodes/doc_' . $document->id . '_' . time() . '.png';
            Storage::put('public/' . $qrPath, $qrCode);

            // Create TandaQr record
            TandaQr::create([
                'data_qr' => $qrString,
                'tanggal_pembuatan' => now(),
                'id_ormawa' => $document->id_ormawa,
                'id_dosen' => auth()->id(),
                'id_dokumen' => $document->id
            ]);

            // Update document with QR code path
            $document->update([
                'qr_code_path' => $qrPath
            ]);

            return response()->json([
                'success' => true,
                'qrCodeUrl' => asset('storage/' . $qrPath)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    public function viewDocument($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        if (!$dokumen->file || !Storage::disk('public')->exists($dokumen->file)) {
            abort(404, 'Document not found');
        }

        $path = Storage::disk('public')->path($dokumen->file);
        $content = file_get_contents($path);
        $mimeType = Storage::disk('public')->mimeType($dokumen->file);

        return response($content)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . basename($dokumen->file) . '"');
    }
}
