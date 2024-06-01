<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('usuario_id')->constrained('users');
            //$table->foreignId('envio_id')->constrained('envios');
            // $table->foreignId('facturacion_id')->constrained('facturaciones');
            $table->dateTime('fecha');
            $table->boolean('enviado')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
