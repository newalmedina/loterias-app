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
        Schema::create('loterie_results', function (Blueprint $table) {
            $table->id();
            $table->date("date");
            $table->string("numbers");
            $table->foreignId('loterie_id')->constrained('loteries')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['loterie_id', 'date'], 'loterie_special_fecha_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loterie_results');
    }
};
