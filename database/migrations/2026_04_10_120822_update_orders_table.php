<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // 🔥 Primero eliminar foreign key
            if (Schema::hasColumn('orders', 'assigned_user_id')) {

                $table->dropForeign(['assigned_user_id']);
                $table->dropColumn('assigned_user_id');
            }

            // iva
            if (Schema::hasColumn('orders', 'iva')) {
                $table->dropColumn('iva');
            }

            // type
            if (Schema::hasColumn('orders', 'type')) {
                $table->dropColumn('type');
            }

            // type nuevo
            // porcentaje_comision
            if (!Schema::hasColumn('orders', 'uuid')) {
                $table->string('uuid',)->unique()->after("id");
            }
            if (!Schema::hasColumn('orders', 'porcentaje_comision')) {
                $table->decimal('porcentaje_comision', 5, 2)->default(0)->after("date");
            }
            if (!Schema::hasColumn('orders', 'qr_image')) {
                $table->string('qr_image')->nullable()->after("date");
            }
            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->dateTime('paid_at')->nullable()->after("date");
            }
            if (!Schema::hasColumn('orders', 'paid_by')) {
                $table->foreignId('paid_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete()->after("paid_at");
            }

            if (!Schema::hasColumn('orders', 'type')) {
                $table->string('type')->default('venta')->after("date");
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            if (Schema::hasColumn('orders', 'paid_by')) {
                // Intentar eliminar la FK primero
                try {
                    $table->dropForeign(['paid_by']);
                } catch (\Exception $e) {
                    // La FK puede no existir, se ignora
                }

                // Luego eliminar la columna
                $table->dropColumn('paid_by');
            }

            if (Schema::hasColumn('orders', 'assigned_user_id')) {
                $table->unsignedBigInteger('assigned_user_id')->nullable();
            }

            if (Schema::hasColumn('orders', 'iva')) {
                $table->decimal('iva', 5, 2)->default(0);
            }

            if (Schema::hasColumn('orders', 'porcentaje_comision')) {
                $table->dropColumn('porcentaje_comision');
            }
            if (Schema::hasColumn('orders', 'qr_image')) {
                $table->dropColumn('qr_image');
            }

            if (Schema::hasColumn('orders', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('orders', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
            if (Schema::hasColumn('orders', 'uuid')) {
                $table->dropColumn('uuid');
            }

            if (!Schema::hasColumn('orders', 'type')) {
                $table->enum('type', ['jugadas'])->default('jugadas');
            }
        });
    }
};
