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
    Schema::create('ventas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
        $table->date('fecha');
        $table->decimal('total', 10, 2);
        $table->enum('metodo_pago', ['nequi', 'daviplata', 'efectivo'])->nullable();
        $table->enum('estado_pago', ['pendiente', 'pagada'])->default('pagada');
        $table->dateTime('fecha_pago')->nullable();
        $table->text('nota')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
