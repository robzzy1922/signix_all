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
        'file',
        'qr_position_x',
        'qr_position_y',
        'qr_width',
        'qr_height',
        'status_dokumen',
        'is_signed',
        'kode_pengesahan',
        'tanggal_verifikasi',
        'keterangan',
        'keterangan_revisi',
        'tanggal_revisi'
    ];

    protected $casts = [
        'tanggal_verifikasi' => 'datetime',
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