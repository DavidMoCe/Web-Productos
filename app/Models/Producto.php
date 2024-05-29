<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Producto extends Model
{
    use HasFactory;

    // Campos que se pueden asignar a la vez, si un atributo no está no se puede crear o actualizar
    protected $fillable = [
        'nombre', 'descripcion', 'precioA', 'precioD', 'color', 'stock', 
        'capacidad', 'libre', 'bateria', 'estado'
    ];

    // Nombre del campo de clave primaria
    protected $primaryKey = 'id';

    /**
     * The pedidos that belong to the producto.
     */
    public function pedidos(): BelongsToMany
    {
        return $this->belongsToMany(Pedidos::class, 'pedidos_productos', 'producto_id', 'pedido_id')
                    ->withPivot('unidades') // Incluir el campo "unidades" de la tabla pivote
                    ->withTimestamps();
    }

    /**
     * The carritos that belong to the producto.
     */
    public function carritos(): BelongsToMany
    {
        return $this->belongsToMany(Carrito::class, 'carrito_producto', 'producto_id', 'carrito_id')
                    ->withPivot('unidades'); // Incluir el campo "unidades" de la tabla pivote
    }

}
