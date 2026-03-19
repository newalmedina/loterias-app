<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loterie_special_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loterie_id')->constrained('loteries')->cascadeOnDelete();
            $table->date('date');
            $table->time('end_time')->nullable();
            $table->boolean('not_enable')->default(false);
            $table->timestamps();

            // Cambiado para permitir varias horas en la misma date
            $table->unique(['loterie_id', 'date', 'end_time'], 'loterie_special_date_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loterie_special_dates');
    }
};
