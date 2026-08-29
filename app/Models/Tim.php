<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tim extends Model
{
    protected $table = 'tim';
    protected $fillable = ['nama_tim', 'deskripsi', 'status'];

    public function pemesanan(): HasMany
    {
        return $this->hasMany(PemesananJasa::class);
    }
}
