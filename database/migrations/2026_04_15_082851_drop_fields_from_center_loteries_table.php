<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('center_loteries', function (Blueprint $table) {
            $table->dropColumn([
                'min_bloqueo',
                'maximo_soportado',
                'primer_premio',
                'segundo_premio',
                'tercer_premio',
                'quarto_premio',
                'quinto_premio',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('center_loteries', function (Blueprint $table) {
            $table->smallInteger('min_bloqueo')->default(10);
            $table->integer('maximo_soportado')->default(0);
            $table->integer('primer_premio')->default(0);
            $table->integer('segundo_premio')->default(0);
            $table->integer('tercer_premio')->default(0);
            $table->integer('quarto_premio')->default(0);
            $table->integer('quinto_premio')->default(0);
        });
    }
};
