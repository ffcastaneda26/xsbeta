<?php

use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostCentersTable extends Migration
{
    public function up()
    {
        Schema::create('cost_centers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class)->constrained()->onDelete('cascade')->comment('Empresa');
            $table->string('code', 15)->unique()->comment('Código');
            $table->string('name', 75)->comment('Nombre');
            $table->text('description')->default(null)->nullable()->comment('Descripción');
            $table->boolean('is_active')->default(true)->comment('¿Activo?');
            // Índices
            $table->index('code');
            $table->index('name');

        });
    }

    public function down()
    {
        Schema::dropIfExists('cost_centers');
    }
}
