<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturacion extends Model
{
    use HasFactory;
    //para evitar problemas con que laravel asuma el nombre de la tabla (generalmente en ingles, plural y minusculas)
    //especificamos el nombre de la tabla
    protected $table = 'facturaciones';

    protected $fillable = [
        'user_id',
        'pais',
        'direccion_1',
        'direccion_2',
        'ciudad',
        'codigo_postal',
        'empresa'      
    ];

    /**
     * Get the user that owns the facturacion.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pedido(){
        return $this->hasOne(Pedidos::class, 'facturacion_id');
    }
}
