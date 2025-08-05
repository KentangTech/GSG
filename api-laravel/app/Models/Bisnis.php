<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bisnis extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'tag',
        'deskripsi',
        'gambar',
    ];

    protected $appends = ['gambar_url'];

    public function getGambarUrlAttribute()
    {
        return $this->gambar
            ? asset('storage/' . $this->gambar)
            : null;
    }
}
