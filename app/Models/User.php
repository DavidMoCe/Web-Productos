<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name','email', 'phone', 'address', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

     /**
     * Get the pedidos associated with the user.
     */
    public function pedidos(): HasMany
    {
        // Relación "hasMany": Un usuario puede tener muchos pedidos
        // Se especifica el nombre del modelo relacionado (Pedido),
        // el nombre de la clave externa en el modelo relacionado (usuario_id),
        // y el nombre de la clave primaria en el modelo actual (CodUsuario)
        return $this->hasMany(Pedidos::class, 'usuario_id','id');
    }

     /**
     * Get the cart associated with the user.
     */
    public function carrito(): HasOne
    {
        // Relación "hasOne": Un usuario tiene un carrito
        // Se especifica el nombre del modelo relacionado (Carrito),
        // el nombre de la clave externa en el modelo relacionado (usuario_id),
        // y el nombre de la clave primaria en el modelo actual (id)
        return $this->hasOne(Carrito::class, 'usuario_id', 'id');
    }
}
