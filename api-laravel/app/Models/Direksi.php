<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direksi extends Model
{
    protected $fillable = ['nama', 'posisi', 'gambar'];
    protected $table = 'direksi';

    public function getGambarUrlAttribute()
    {
        return $this->gambar ? asset('storage/direksi/' . $this->gambar) : null;
    }
}
