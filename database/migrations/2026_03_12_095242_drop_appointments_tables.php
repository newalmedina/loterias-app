<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Deshabilitamos temporalmente las FK para evitar errores
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Primero tablas hijas
        Schema::dropIfExists('appointment_items');           // depende de appointments
        Schema::dropIfExists('appointment_template_slots'); // depende de appointment_templates
        Schema::dropIfExists('appointments');               // depende de appointment_templates

        // Ahora tablas padres
        Schema::dropIfExists('appointment_templates');

        // Otras tablas sin dependencias
        Schema::dropIfExists('calendars');
        Schema::dropIfExists('departaments');
        Schema::dropIfExists('holidays');
        Schema::dropIfExists('other_expense_items');
        Schema::dropIfExists('other_expense_details');
        Schema::dropIfExists('other_expenses');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('unit_of_measures');
        Schema::dropIfExists('user_calendars');
        Schema::dropIfExists('user_departaments');

        // Volvemos a habilitar las FK
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down()
    {
        // Opcional: recreación mínima (solo id y timestamps)
        Schema::create('appointment_templates', function ($table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('appointments', function ($table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('appointment_items', function ($table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('appointment_template_slots', function ($table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('calendars', function ($table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('departaments', function ($table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('holidays', function ($table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('other_expenses', function ($table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('other_expense_details', function ($table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('other_expense_items', function ($table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('tasks', function ($table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('unit_of_measures', function ($table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('user_calendars', function ($table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('user_departaments', function ($table) {
            $table->id();
            $table->timestamps();
        });
    }
};
