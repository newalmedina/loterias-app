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
        Schema::table('loterie_results', function (Blueprint $table) {
            $table->datetime("spain_updated_at")->nullable()->after("updated_at");
            $table->datetime("spain_created_at")->nullable()->after("updated_at");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loterie_results', function (Blueprint $table) {
            $table->dropColumn("spain_created_at");
            $table->dropColumn("spain_updated_at");
        });
    }
};
