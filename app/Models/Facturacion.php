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
        'empresa',
        'nif_dni'     
    ];

    /**
     * Get the user that owns the facturacion.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pedido(){
        return $this->hasOne(Pedido::class, 'facturacion_id');
    }
}
