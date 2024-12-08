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
<<<<<<< HEAD
=======
        'status_dokumen',
        'qr_code_path',
>>>>>>> e047187b14b1b34a520e726854aacc1dedb6a069
        'qr_position_x',
        'qr_position_y',
        'qr_width',
        'qr_height',
<<<<<<< HEAD
        'status_dokumen',
        'is_signed',
        'qr_code_path',
        'kode_pengesahan',
        'tanggal_verifikasi'
    ];

    protected $casts = [
        'tanggal_verifikasi' => 'datetime',
=======
        'is_signed',
        'kode_pengesahan'
>>>>>>> e047187b14b1b34a520e726854aacc1dedb6a069
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