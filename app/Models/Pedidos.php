<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pedidos extends Model
{
    use HasFactory;

    protected $fillable = ['fecha', 'enviado', 'usuario_id'];
    /**
     * Get the user that owns the pedido.
     */
    public function usuario(): BelongsTo
    {
        // Relación "belongsTo": Un pedido pertenece a un usuario
        // Se especifica el nombre del modelo relacionado (User),
        // el nombre de la clave externa en el modelo actual (usuario_id),
        // y el nombre de la clave primaria en el modelo relacionado (id)
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }

    /**
     * Get the shipping address associated with the order.
     */
    public function direccionEnvio(): BelongsTo{
        return $this->belongsTo(Envio::class, 'envio_id');
    }

    /**
     * Get the billing address associated with the order.
     */
    public function direccionFacturacion(): BelongsTo{
        return $this->belongsTo(Facturacion::class, 'facturacion_id');
    }

    /**
     * The products that belong to the pedido.
     */
    public function productos(): BelongsToMany
    {
        // Relación "belongsToMany": Un pedido tiene muchos productos a través de la tabla pivote pedidos_productos
        return $this->belongsToMany(Producto::class, 'pedidos_productos', 'pedido_id', 'producto_id')->withPivot('unidades')->withTimestamps();
        // Incluir el campo "unidades" de la tabla pivote
    }
}
