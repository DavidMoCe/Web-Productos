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
        Schema::table('productos', function (Blueprint $table) {
            $table->integer('capacidad')->after('nombre');
            $table->integer('libre')->after('color');
            $table->integer('bateria')->after('libre');
            $table->string('estado', 20)->after('bateria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['capacidad', 'libre', 'bateria', 'estado']);
        });
    }
};
