<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pais',
        'direccion_1',
        'direccion_2',
        'ciudad',
        'codigo_postal',
        'empresa',
        'telefono'        
    ];

    /**
     * Get the user that owns the envio.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pedido(){
        return $this->hasOne(Pedidos::class, 'envio_id');
    }
}
