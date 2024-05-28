<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Carrito extends Model
{
    use HasFactory;
    protected $table = 'carrito';
    protected $fillable = [
        'usuario_id',
    ];

    /**
     * Get the user that owns the carrito.
     */
    public function usuario(): BelongsTo
    {
        // Relación "belongsTo": Un carrito pertenece a un usuario
        // Se especifica el nombre del modelo relacionado (User),
        // el nombre de la clave externa en el modelo actual (usuario_id),
        // y el nombre de la clave primaria en el modelo relacionado (id)
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }

    /**
     * The productos that belong to the carrito.
     */
    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'carrito_producto', 'carrito_id', 'producto_id')
                    ->withPivot('unidades'); // Incluir el campo "unidades" de la tabla pivote
    }
}
