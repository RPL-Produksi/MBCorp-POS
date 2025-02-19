<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'nama',
        'harga',
        'kategori_id',
        'perusahaan_id',
        'kode',
        'stok',
        'deskripsi',
        'foto',
    ];
}
