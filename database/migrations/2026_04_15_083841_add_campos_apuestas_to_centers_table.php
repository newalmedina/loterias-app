<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('centers', function (Blueprint $table) {
            $table->integer('min_antes_bloqueo')->default(10)->after('postal_code');
            $table->integer('monto_maximo_quinielas')->default(0)->after('min_antes_bloqueo');

            $table->integer('quiniela_primer_premio')->default(0)->after('monto_maximo_quinielas');
            $table->integer('quiniela_monto_soportado')->default(2)->after('monto_maximo_quinielas');
            $table->integer('quiniela_segundo_premio')->default(0)->after('quiniela_primer_premio');
            $table->integer('quiniela_tercer_premio')->default(0)->after('quiniela_segundo_premio');

            $table->integer('pale_primer_premio')->default(0)->after('quiniela_tercer_premio');
            $table->integer('pale_monto_soportado')->default(2)->after('quiniela_tercer_premio');
            $table->integer('pale_segundo_premio')->default(0)->after('pale_primer_premio');
            $table->integer('pale_tercer_premio')->default(0)->after('pale_segundo_premio');

            $table->integer('suppale_primer_premio')->default(0)->after('pale_tercer_premio');
            $table->integer('suppale_monto_soportado')->default(2)->after('pale_tercer_premio');
            $table->integer('suppale_segundo_premio')->default(0)->after('suppale_primer_premio');
            $table->integer('suppale_tercer_premio')->default(0)->after('suppale_segundo_premio');

            $table->integer('tripleta_primer_premio')->default(0)->after('suppale_tercer_premio');
            $table->integer('tripleta_monto_soportado')->default(2)->after('suppale_tercer_premio');
            $table->integer('tripleta_segundo_premio')->default(0)->after('tripleta_primer_premio');
            $table->integer('tripleta_tercer_premio')->default(0)->after('tripleta_segundo_premio');
        });
    }

    public function down(): void
    {
        Schema::table('centers', function (Blueprint $table) {
            $table->dropColumn([
                'min_antes_bloqueo',
                'quiniela_monto_soportado',
                'pale_monto_soportado',
                'tripleta_monto_soportado',
                'suppale_monto_soportado',
                'monto_maximo_quinielas',
                'quiniela_primer_premio',
                'quiniela_segundo_premio',
                'quiniela_tercer_premio',
                'pale_primer_premio',
                'pale_segundo_premio',
                'pale_tercer_premio',
                'suppale_primer_premio',
                'suppale_segundo_premio',
                'suppale_tercer_premio',
                'tripleta_primer_premio',
                'tripleta_segundo_premio',
                'tripleta_tercer_premio',
            ]);
        });
    }
};
