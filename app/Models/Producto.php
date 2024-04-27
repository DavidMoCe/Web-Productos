<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // Campos que se pueden asignar a la vez, si un atributo no está no se puede crear o actualizar
    protected $fillable = ['nombre', 'descripcion', 'precio', 'color', 'stock'];

    // Nombre del campo de clave primaria
    protected $primaryKey = 'codProd';

}
