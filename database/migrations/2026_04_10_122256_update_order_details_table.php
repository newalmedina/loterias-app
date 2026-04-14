<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_details', function (Blueprint $table) {

            // 🔥 Drop columns antiguas
            if (Schema::hasColumn('order_details', 'product_name')) {
                $table->dropColumn('product_name');
            }

            if (Schema::hasColumn('order_details', 'original_price')) {
                $table->dropColumn('original_price');
            }

            if (Schema::hasColumn('order_details', 'price')) {
                $table->dropColumn('price');
            }

            if (Schema::hasColumn('order_details', 'taxes')) {
                $table->dropColumn('taxes');
            }

            if (Schema::hasColumn('order_details', 'quantity')) {
                $table->dropColumn('quantity');
            }

            // ➕ nuevas columnas

            if (!Schema::hasColumn('order_details', 'loterie_id')) {
                $table->unsignedBigInteger('loterie_id')->after('order_id');
                $table->foreign('loterie_id')
                    ->references('id')
                    ->on('loteries')
                    ->onDelete('cascade');
            }

            if (!Schema::hasColumn('order_details', 'second_loterie_id')) {
                $table->unsignedBigInteger('second_loterie_id')->nullable()->after('loterie_id');
                $table->foreign('second_loterie_id')
                    ->references('id')
                    ->on('loteries')
                    ->onDelete('set null');
            }
            if (!Schema::hasColumn('order_details', 'monto_jugada')) {
                $table->decimal('monto_jugada', 10, 2)->default(0)->after("second_loterie_id");
            }

            if (!Schema::hasColumn('order_details', 'premiado_posicion')) {
                $table->string('premiado_posicion')->nullable()->after("second_loterie_id");
            }
            if (!Schema::hasColumn('order_details', 'premiado')) {
                $table->boolean('premiado')->default(0)->after("second_loterie_id");
            }

            if (!Schema::hasColumn('order_details', 'monto_premio')) {
                $table->decimal('monto_premio', 10, 2)->default(0)->after("second_loterie_id");
            }

            if (!Schema::hasColumn('order_details', 'monto_por_unidad')) {
                $table->decimal('monto_por_unidad', 10, 2)->default(0)->after("second_loterie_id");
            }
            if (!Schema::hasColumn('order_details', 'type')) {
                $table->char('type', 5)->nullable()->after("second_loterie_id");
            }
            if (!Schema::hasColumn('order_details', 'number')) {
                $table->char('number', 10)->nullable()->after("second_loterie_id");
            }

            if (Schema::hasColumn('orders', 'item_id')) {

                $table->dropForeign(['item_id']);
                $table->dropColumn('item_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {

            // 🔥 quitar FKs primero
            if (Schema::hasColumn('order_details', 'loterie_id')) {
                $table->dropForeign(['loterie_id']);
            }

            if (Schema::hasColumn('order_details', 'second_loterie_id')) {
                $table->dropForeign(['second_loterie_id']);
            }

            // ➖ drop nuevas columnas
            $table->dropColumn([
                'loterie_id',
                'second_loterie_id',
                'monto_jugada',
                'premiado',
                'monto_premio',
                'monto_por_unidad',
                'type',
                'number',
                'premiado_posicion',
            ]);

            // 🔄 restaurar antiguas
            $table->string('product_name')->nullable();
            $table->decimal('original_price', 10, 2)->nullable();
            $table->decimal('price', 10, 2)->default(1);
            $table->decimal('taxes', 10, 2)->default(1);
            $table->integer('quantity')->nullable();
        });
    }
};
