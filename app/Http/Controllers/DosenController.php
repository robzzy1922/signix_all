<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Dokumen;
use setasign\Fpdi\Fpdi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\Image\EpsImageBackEnd;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Models\TandaQr;

class DosenController extends Controller
{
    public function dashboardDosen(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');
        $dosen_id = auth()->guard('dosen')->user()->id;

        $dokumens = Dokumen::with('dosen') // Eager loading relasi dosen
    ->where('id_dosen', $dosen_id)
    ->when($status, function ($query) use ($status) {
        return $query->where('status_dokumen', $status);
    })
    ->when($search, function ($query) use ($search) {
        return $query->where(function ($q) use ($search) {
            $q->where('nomor_surat', 'like', "%{$search}%")
              ->orWhere('tanggal_pengajuan', 'like', "%{$search}%")
              ->orWhere('perihal', 'like', "%{$search}%")
              ->orWhereHas('dosen', function ($q) use ($search) {
                  $q->where('nama_dosen', 'like', "%{$search}%");
              })
              ->orWhere('status_dokumen', 'like', "%{$search}%");
        });
    })
    ->get();


        $countDiajukan = Dokumen::where('id_dosen', $dosen_id)
            ->where('status_dokumen', 'diajukan')->count();
        $countDisahkan = Dokumen::where('id_dosen', $dosen_id)
            ->where('status_dokumen', 'disahkan')->count();
        $countRevisi = Dokumen::where('id_dosen', $dosen_id)
            ->where('status_dokumen', 'direvisi')->count();

        return view('user.dosen.dashboard_dosen', compact('dokumens', 'status', 'countDiajukan', 'countDisahkan', 'countRevisi'));
    }

    public function create()
    {
        return view('user.dosen.create_tandatangan');
    }

    public function riwayat(Request $request)
    {
        $query = Dokumen::query()->where('id_dosen', Auth::guard('dosen')->id());

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status_dokumen', $request->status);
        }

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%");
            });
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('user.dosen.riwayat_dosen', compact('documents'));
    }

    public function showDokumen($id)
    {
        $dosen_id = auth()->guard('dosen')->user()->id;

        $dokumen = Dokumen::with(['ormawa', 'dosen'])
            ->where('id_dosen', $dosen_id)
            ->where('id', $id)
            ->firstOrFail();

        return response()->json([
            'id' => $dokumen->id,
            'nomor_surat' => $dokumen->nomor_surat,
            'tanggal_pengajuan' => $dokumen->tanggal_pengajuan,
            'perihal' => $dokumen->perihal,
            'status_dokumen' => ucfirst($dokumen->status_dokumen),
            'keterangan' => $dokumen->keterangan,
            'file' => $dokumen->file,
            'pengaju' => $dokumen->ormawa ? [
                'nama' => $dokumen->ormawa->namaMahasiswa,
                'ormawa' => $dokumen->ormawa->namaOrmawa,
            ] : null,
            'dosen' => $dokumen->dosen ? [
                'nama' => $dokumen->dosen->nama_dosen,
            ] : null,
        ]);
    }

    public function getDokumenDetail($id)
    {
        $dokumen = Dokumen::with(['ormawa', 'dosen'])->findOrFail($id);
        return response()->json($dokumen);
    }

    public function profile()
    {
        $dosen = Auth::guard('dosen')->user();
        return view('user.dosen.profile', compact('dosen'));
    }

    public function editProfile()
    {
        $dosen = Auth::guard('dosen')->user();
        return view('user.dosen.edit_profile', compact('dosen'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'namaMahasiswa' => 'required|string|max:255',
            'email' => 'required|email',
            'noHp' => 'required|string|max:15',
        ]);

        $dosen = Auth::guard('dosen')->user();
        $dosen->update([
            'nama_dosen' => $request->nama_dosen,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ]);
        $dosen->save();

        return redirect()->route('dosen.profile')->with('success', 'Profile updated successfully');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => ['required', 'image', 'max:1024'] // 1MB Max
        ]);

        $dosen = Auth::guard('dosen')->user();

        if ($dosen->profile) {
            Storage::disk('public')->delete($dosen->profile);
        }

        $path = $request->file('profile_photo')->store('profile-photos', 'public');

        $dosen->update([
            'profile' => $path
        ]);

        return back()->with('success', 'Profile photo updated successfully');
    }

    public function destroyPhoto()
    {
        $dosen = Auth::guard('dosen')->user();

        if ($dosen->profile) {
            Storage::disk('public')->delete($dosen->profile);

            $dosen->update([
                'profile' => null
            ]);
        }

        return back()->with('success', 'Profile photo removed successfully');
    }

    public function logout(Request $request)
    {
        Auth::guard('dosen')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout');
    }

    public function generateQrCode($id)
    {
        try {
            $dokumen = Dokumen::findOrFail($id);

            // Generate kode pengesahan jika belum ada
            if (!$dokumen->kode_pengesahan) {
                $dokumen->kode_pengesahan = Str::random(10);
                $dokumen->save();
            }

            // Buat URL verifikasi
            $verificationUrl = url("/verify/document/{$id}?kode={$dokumen->kode_pengesahan}");

            // Generate QR Code dengan path yang benar
            $qrCodePath = 'qrcodes/qr_' . $id . '_' . time() . '.png';
            $fullPath = storage_path('app/public/' . $qrCodePath);

            // Pastikan direktori exists
            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            // Generate QR code menggunakan SimpleSoftwareIO
            QrCode::format('png')
                  ->size(400)
                  ->margin(1)
                  ->generate($verificationUrl, $fullPath);

            // Simpan data ke tabel tanda_qrs
            TandaQr::create([
                'data_qr' => $verificationUrl,
                'tanggal_pembuatan' => now(),
                'id_ormawa' => $dokumen->id_ormawa,
                'id_dosen' => auth()->guard('dosen')->id(),
                'id_dokumen' => $dokumen->id
            ]);

            // Update dokumen dengan path QR code
            $dokumen->update([
                'qr_code_path' => $qrCodePath
            ]);

            return response()->json([
                'success' => true,
                'qrCodeUrl' => Storage::disk('public')->url($qrCodePath),
                'message' => 'QR Code berhasil dibuat'
            ]);

        } catch (\Exception $e) {
            Log::error('QR Code Generation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat QR Code: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveQrPosition(Request $request, Dokumen $dokumen)
    {
        try {
            $validated = $request->validate([
                'x' => 'required|numeric',
                'y' => 'required|numeric',
                'width' => 'required|numeric',
                'height' => 'required|numeric',
                'page' => 'required|numeric'
            ]);

            if (!$dokumen->qr_code_path || !Storage::disk('public')->exists($dokumen->qr_code_path)) {
                throw new \Exception('QR Code belum di-generate');
            }

            $sourcePdfPath = storage_path('app/public/' . $dokumen->file);
            if (!file_exists($sourcePdfPath)) {
                throw new \Exception('File PDF sumber tidak ditemukan');
            }

            // Inisialisasi FPDI
            $pdf = new \setasign\Fpdi\Fpdi();
            $pageCount = $pdf->setSourceFile($sourcePdfPath);

            // Proses setiap halaman
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $pdf->AddPage();
                $tplIdx = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplIdx);

                // Tambahkan QR code hanya di halaman yang dipilih
                if ($pageNo === (int)$validated['page']) {
                    $qrCodePath = storage_path('app/public/' . $dokumen->qr_code_path);

                    // Dapatkan ukuran halaman
                    $pageWidth = $pdf->GetPageWidth();
                    $pageHeight = $pdf->GetPageHeight();

                    // Konversi persentase ke koordinat absolut
                    $x = ($validated['x'] * $pageWidth) / 100;
                    $y = ($validated['y'] * $pageHeight) / 100;
                    $width = ($validated['width'] * $pageWidth) / 100;
                    $height = ($validated['height'] * $pageHeight) / 100;

                    // Pastikan QR code tidak keluar dari halaman
                    $x = max(0, min($x, $pageWidth - $width));
                    $y = max(0, min($y, $pageHeight - $height));

                    // Tambahkan QR code ke PDF
                    $pdf->Image($qrCodePath, $x, $y, $width, $height);
                }
            }

            // Simpan PDF yang sudah ditandatangani
            $newFileName = 'signed_' . time() . '_' . basename($dokumen->file);
            $newFilePath = 'dokumen/' . $newFileName;

            // Pastikan direktori exists
            $fullPath = storage_path('app/public/' . $newFilePath);
            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            // Simpan PDF ke storage
            $pdf->Output($fullPath, 'F');

            // Update database dengan timestamp yang benar
            $dokumen->update([
                'file' => $newFilePath,
                'qr_position_x' => $validated['x'],
                'qr_position_y' => $validated['y'],
                'qr_width' => $validated['width'],
                'qr_height' => $validated['height'],
                'qr_page' => $validated['page'],
                'status_dokumen' => 'disahkan',
                'is_signed' => true,
                'tanggal_verifikasi' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'QR Code berhasil ditambahkan dan dokumen telah disahkan'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in saveQrPosition: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyDocument($id)
    {
        try {
            $dokumen = Dokumen::with(['dosen', 'ormawa'])->findOrFail($id);

            if (!$dokumen->is_signed || !$dokumen->kode_pengesahan) {
                return view('verify.document', [
                    'verified' => false,
                    'message' => 'Dokumen belum disahkan'
                ]);
            }

            return view('verify.document', [
                'dokumen' => $dokumen,
                'title' => 'Verifikasi Dokumen',
                'verified' => true,
                'timestamp' => now()->format('d M Y H:i:s')
            ]);
        } catch (\Exception $e) {
            return view('verify.document', [
                'verified' => false,
                'message' => 'Dokumen tidak ditemukan'
            ]);
        }
    }

    public function editQrCode($id)
    {
        try {
            $dokumen = Dokumen::findOrFail($id);

            if ($dokumen->id_dosen != auth()->id()) {
                abort(403, 'Unauthorized action.');
            }

            // Generate QR code jika belum ada
            if (!$dokumen->qr_code_path || !Storage::disk('public')->exists($dokumen->qr_code_path)) {
                // Generate kode pengesahan baru
                $dokumen->kode_pengesahan = Str::random(10);

                // Set path QR code
                $qrCodePath = 'qrcodes/qr_' . $dokumen->id . '_' . time() . '.png';
                $fullPath = storage_path('app/public/' . $qrCodePath);

                // Buat direktori jika belum ada
                if (!file_exists(dirname($fullPath))) {
                    mkdir(dirname($fullPath), 0755, true);
                }

                // Generate QR code
                QrCode::format('png')
                      ->size(400)
                      ->margin(1)
                      ->generate(
                          url("/verify/document/{$dokumen->id}?kode={$dokumen->kode_pengesahan}"),
                          $fullPath
                      );

                // Update dokumen
                $dokumen->update([
                    'qr_code_path' => $qrCodePath,
                    'kode_pengesahan' => $dokumen->kode_pengesahan
                ]);
            }

            return view('user.dosen.edit_qr', compact('dokumen'));

        } catch (\Exception $e) {
            Log::error('Error in editQrCode: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat QR Code: ' . $e->getMessage());
        }
    }
}