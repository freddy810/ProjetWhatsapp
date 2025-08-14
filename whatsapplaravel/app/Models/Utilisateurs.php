<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utilisateurs extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'type', 'prenom', 'dateNaissance', 'telephone', 'photoProfil', 'status', 'motDePasse'];

    public function messagesEnvoyes()
    {
        return $this->hasMany(Messages::class, 'utilisateurEnvoyeurMessage_id');
    }

    public function messagesRecus()
    {
        return $this->hasMany(Messages::class, 'utilisateurReceveurMessage_id');
    }

    public function appelsEnvoyes()
    {
        return $this->hasMany(Appels::class, 'utilisateurEnvoyeur_id');
    }

    public function appelsRecus()
    {
        return $this->hasMany(Appels::class, 'utilisateurReceveur_id');
    }
}
