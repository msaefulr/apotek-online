<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';

    protected $fillable = [
        'nama_pelanggan',
        'email',
        'katakunci',
        'no_telp',

        'alamat1',
        'kota1',
        'provinsi1',
        'kodepos1',
        'alamat2',
        'kota2',
        'provinsi2',
        'kodepos2',
        'alamat3',
        'kota3',
        'provinsi3',
        'kodepos3',

        'url_ktp',
        'url_foto',
    ];
}
