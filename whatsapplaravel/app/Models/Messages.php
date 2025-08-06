<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;
    protected $fillable = ['contenues', 'typeMessage', 'dateMessage', 'utilisateurReceveurMessage_id', 'utilisateurEnvoyeurMessage_id'];


    public function envoyeurMessage()
    {
        return $this->belongsTo(Utilisateurs::class, 'utilisateurEnvoyeurMessage_id');
    }

    public function receveurMessage()
    {
        return $this->belongsTo(Utilisateurs::class, 'utilisateurReceveurMessage_id');
    }
}
