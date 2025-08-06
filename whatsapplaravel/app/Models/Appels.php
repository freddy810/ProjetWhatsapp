<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appels extends Model
{
    use HasFactory;
    protected $fillable = ['type', 'status', 'dateAppel', 'utilisateurEnvoyeur_id', 'utilisateurReceveur_id'];

    public function envoyeurAppel()
    {
        return $this->belongsTo(Utilisateurs::class, 'utilisateurEnvoyeur_id');
    }

    public function receveurAppel()
    {
        return $this->belongsTo(Utilisateurs::class, 'utilisateurReceveur_id');
    }
}
