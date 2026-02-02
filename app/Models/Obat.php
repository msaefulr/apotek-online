<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';

    protected $fillable = ['nama_obat', 'idjenis', 'harga_jual', 'deskripsi_obat', 'foto1', 'foto2', 'foto3', 'stok'];

    public function jenis()
    {
        return $this->belongsTo(JenisObat::class, 'idjenis');
    }
}
