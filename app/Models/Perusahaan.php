<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'nama',
        'alamat',
        'nomor_telp',
        'email',
    ];

    public function owner()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function kasir()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
