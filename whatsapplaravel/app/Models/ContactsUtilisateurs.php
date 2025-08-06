<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactsUtilisateurs extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'prenom', 'numPhone', 'utilisateurPossedantContact_id'];

    public function possedeurMessage()
    {
        return $this->belongsTo(Utilisateurs::class, 'utilisateurPossedantContact_id');
    }
}
