<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loteries', function (Blueprint $table) {
            $table->id();
            $table->string('short_name', 20)->nullable();
            $table->string('nombre');
            $table->string('slug', 100)->nullable()->unique();
            $table->string('code', 20)->unique();
            $table->string('image')->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('active')->default(true);

            // Horas de fin por día
            $table->string('time_zone')->nullable();
            $table->time('lunes_hora_fin')->nullable();
            $table->time('martes_hora_fin')->nullable();
            $table->time('miercoles_hora_fin')->nullable();
            $table->time('jueves_hora_fin')->nullable();
            $table->time('viernes_hora_fin')->nullable();
            $table->time('sabado_hora_fin')->nullable();
            $table->time('domingo_hora_fin')->nullable();

            // Disponibilidad por día
            $table->boolean('lunes_disponible')->default(true);
            $table->boolean('martes_disponible')->default(true);
            $table->boolean('miercoles_disponible')->default(true);
            $table->boolean('jueves_disponible')->default(true);
            $table->boolean('viernes_disponible')->default(true);
            $table->boolean('sabado_disponible')->default(true);
            $table->boolean('domingo_disponible')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loteries');
    }
};
