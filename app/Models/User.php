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
        'name', 'lastname', 'email', 'phone', 'password',
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
    public function pedido(): HasMany
    {
        // Relación "hasMany": Un usuario puede tener muchos pedidos
        // Se especifica el nombre del modelo relacionado (Pedido),
        // el nombre de la clave externa en el modelo relacionado (usuario_id),
        // y el nombre de la clave primaria en el modelo actual (id) aunque no hace falta este segundo campo si es "id"
        return $this->hasMany(Pedido::class, 'usuario_id','id');
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

    public function direccionEnvio(): HasOne{
        return $this->hasOne(Envio::class, 'user_id');
    }

    public function direccionFacturacion(): HasOne{
        return $this->hasOne(Facturacion::class, 'user_id');
    }

    /**
     * Get the shipping information associated with the user.
     */
    public function envio(): HasOne{
        return $this->hasOne(Envio::class, 'user_id', 'id');
    }
    

     /**
     * Get the password reset tokens associated with the user.
     */
    public function passwordResetTokens(): HasMany{
        return $this->hasMany(PasswordResetToken::class, 'email', 'email');
    }

    /**
     * Get the sessions associated with the user.
     */
    public function sessions(): HasMany{
        return $this->hasMany(Session::class, 'user_id');
    }

}
