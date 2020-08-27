<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Usuario extends Model
{
    use Notifiable, HasApiTokens;
    protected $guarded = ['id_usuario'];
}
