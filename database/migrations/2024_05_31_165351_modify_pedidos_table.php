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
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('envio_id')->references('id')->on('envios');
            $table->foreign('facturacion_id')->references('id')->on('facturaciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign(['facturacion_id']);
            $table->dropColumn(['facturacion_id']);
        });
    }
};
