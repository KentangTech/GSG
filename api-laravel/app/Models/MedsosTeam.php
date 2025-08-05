<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedsosTeam extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'username', 'image'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : null;
    }

    public function platforms()
    {
        return $this->hasMany(SocialPlatform::class, 'medsos_team_id');
    }
}
