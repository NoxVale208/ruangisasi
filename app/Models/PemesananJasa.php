<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PemesananJasa extends Model
{
    protected $table = 'pemesanan_jasa';

    protected $fillable = [
        'user_id',
        'jasa_id',
        'nama_jasa',
        'alamat',
        'tanggal_mulai',
        'tanggal_selesai',
        'budget',
        'status_persetujuan',
        'status_proses',
        'keputusan',
        'diputuskan_oleh',
        'diputuskan_pada',
        'tim_id',
        'catatan_admin',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'budget' => 'integer',
            'diputuskan_pada' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jasa(): BelongsTo
    {
        return $this->belongsTo(Jasa::class);
    }

    public function tim(): BelongsTo
    {
        return $this->belongsTo(Tim::class);
    }

    public function diputuskanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diputuskan_oleh');
    }
}
