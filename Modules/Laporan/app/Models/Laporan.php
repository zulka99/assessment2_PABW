<?php

namespace Modules\Laporan\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\TitikDrainase\Models\TitikDrainase;

class Laporan extends Model
{
    protected $fillable = [
        'user_id',
        'titik_drainase_id',
        'tinggi_air_cm',
        'status_kondisi',
        'deskripsi',
        'foto_bukti',
    ];

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fasilitas()
    {
        return $this->belongsTo(TitikDrainase::class, 'titikdrainase_id');
    }
}