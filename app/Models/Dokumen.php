<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Ormawas;
use App\Models\Dosen;

class Dokumen extends Model
{
    use HasFactory;
    protected $table = 'dokumens';

    protected $fillable = [
        'nomor_surat',
        'tanggal_pengajuan',
        'perihal',
        'file',
        'status_dokumen',
        'qr_code_path',
        'qr_position_x',
        'qr_position_y',
        'qr_width',
        'qr_height',
        'is_signed',
        'kode_pengesahan'
    ];

    // Relationship with Ormawa
    public function ormawa()
    {
        return $this->belongsTo(Ormawas::class, 'id_ormawa');
    }

    // Relationship with Dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }
}