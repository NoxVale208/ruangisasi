<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusPemesanan extends Model
{
    protected $table = 'status_pemesanan';

    protected $fillable = [
        'pemesanan_jasa_id',
        'status',
        'catatan',
        'diubah_oleh',
        'diubah_pada',
    ];

    protected function casts(): array
    {
        return [
            'diubah_pada' => 'datetime',
        ];
    }

    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(PemesananJasa::class, 'pemesanan_jasa_id');
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
}
