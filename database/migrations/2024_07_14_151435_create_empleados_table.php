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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
                        // Información básica
            $table->string('tipo_identificacion', 2); // CC, CE, TI, etc.
            $table->string('numero_identificacion', 20)->unique();
            $table->string('primer_nombre', 50);
            $table->string('segundo_nombre', 50)->nullable();
            $table->string('primer_apellido', 50);
            $table->string('segundo_apellido', 50)->nullable();

            // Información de contacto
            $table->string('telefono', 20)->nullable();
            $table->string('correo_electronico', 100)->nullable();

            // Información laboral
            $table->string('cargo', 100);
            $table->date('fecha_ingreso');
            $table->date('fecha_retiro')->nullable(); // En caso de que ya no trabaje
            $table->decimal('salario_basico', 10, 2);
            $table->string('tipo_contrato', 50); // Indefinido, fijo, obra o labor, etc.

            // Para facturación electrónica
            $table->string('codigo_trabajador', 20)->nullable(); // Si se usa
            $table->string('tipo_cotizante', 2); // Dependiente, independiente, etc.
            $table->string('subtipo_cotizante', 2); // 00, 01, 02, etc.
            $table->boolean('pensionado')->default(false);

            // Información adicional (opcional)
            $table->string('direccion', 255)->nullable();
            $table->string('ciudad_id', 50)->nullable();
            $table->string('departamento_is', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
