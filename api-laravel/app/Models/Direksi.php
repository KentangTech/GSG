<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'posisi',
        'foto',
    ];

    // Akses URL foto
    public function getFotoUrlAttribute()
    {
        return $this->foto ? asset('storage/' . $this->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($this->nama) . '&background=4361ee&color=fff';
    }
}
