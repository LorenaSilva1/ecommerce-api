<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atributos que podem ser preenchidos em massa
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 
    ];

    /**
     * Atributos que devem ser ocultados nas respostas JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversões automáticas de tipo
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Verifica se o usuário é administrador
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
