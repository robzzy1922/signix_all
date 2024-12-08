<?php

namespace App\Jobs;

use App\Models\Dokumen;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use setasign\Fpdi\Fpdi;

class ProcessQRCode implements ShouldQueue
{
    use Queueable;

    protected $dokumenId;
    protected $x;
    protected $y;
    protected $width;
    protected $height;

    /**
     * Create a new job instance.
     *
     * @param int $dokumenId
     * @param float $x
     * @param float $y
     * @param float $width
     * @param float $height
     */
    public function __construct($dokumenId, $x, $y, $width, $height)
    {
        $this->dokumenId = $dokumenId;
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $dokumen = Dokumen::findOrFail($this->dokumenId);
        $sourcePdfPath = storage_path('app/public/' . $dokumen->file);
        $pdf = new FPDI();
        $pageCount = $pdf->setSourceFile($sourcePdfPath);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $pdf->AddPage();
            $tplIdx = $pdf->importPage($pageNo);
            $pdf->useTemplate($tplIdx);

            if ($pageNo === 1) {
                $qrCodePath = storage_path('app/public/' . $dokumen->qr_code_path);
                $pdf->Image($qrCodePath, $this->x, $this->y, $this->width, $this->height);
            }
        }

        $newFilePath = 'dokumen/updated_' . time() . '_' . basename($dokumen->file);
        Storage::disk('public')->put($newFilePath, $pdf->Output('S'));
        $dokumen->update(['file' => $newFilePath]);
    }
}