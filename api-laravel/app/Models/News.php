<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar',
        'update_time',
        'uploaded_by',
    ];

    protected $appends = ['gambar_url'];

    // Relasi: uploaded_by → User
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Akses URL gambar
    public function getGambarUrlAttribute()
    {
        return $this->gambar
            ? asset('storage/' . $this->gambar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->judul) . '&background=4361ee&color=fff';
    }

    protected $casts = [
        'update_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
