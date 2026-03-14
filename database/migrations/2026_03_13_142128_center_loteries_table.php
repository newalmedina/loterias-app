<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('center_loteries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('center_id')
                ->constrained('centers')
                ->cascadeOnDelete();

            $table->foreignId('loterie_id')
                ->constrained('loteries')
                ->cascadeOnDelete();

            $table->boolean('active')->default(true);
            $table->smallInteger('min_bloqueo')->default(10);
            $table->integer('maximo_soportado')->default(0);

            $table->timestamps();

            $table->unique(['center_id', 'loterie_id'], 'center_loterie_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('center_loteries');
    }
};
