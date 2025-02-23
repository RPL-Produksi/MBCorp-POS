<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasUuids;

    protected $fillable = [
        'produk_id',
        'jumlah',
        'total',
        'kasir_id',
        'perusahaan_id',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function kasir()
    {
        return $this->belongsTo(Kasir::class);
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }
}
