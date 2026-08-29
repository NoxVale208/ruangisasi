<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jasa extends Model
{
    protected $table = 'jasa';
    protected $fillable = ['nama', 'deskripsi', 'status'];

    public function pemesanan(): HasMany
    {
        return $this->hasMany(PemesananJasa::class);
    }
}
