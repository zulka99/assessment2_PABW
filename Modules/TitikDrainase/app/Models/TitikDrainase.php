<?php

namespace Modules\Fasilitas\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Laporan\Models\Laporan;

class TitikDrainase extends Model
{
    protected $table = 'titikdrainase';

    protected $fillable = [
        'nama_titik',
        'kecamatan',
        'deskripsi',
        'kedalaman_cm',
    ];

    public function pengaduans()
    {
        return $this->hasMany(Laporan::class, 'titikdrainase_id');
    }
}