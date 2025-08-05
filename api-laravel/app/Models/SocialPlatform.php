<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialPlatform extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'url', 'medsos_team_id'];

    public function team()
    {
        return $this->belongsTo(MedsosTeam::class, 'medsos_team_id');
    }
}
