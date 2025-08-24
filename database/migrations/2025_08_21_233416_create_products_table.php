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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nombre');
            $table->string('slug')->unique();
            $table->text('description')->nullable()->comment('Descripcióno');
            $table->string('short_description')->nullable()->comment('Descripción corta');
            $table->decimal('price', 10, 2)->comment('Precio');
            $table->unsignedInteger('stock')->default(0)->comment('Existencia');
            $table->json('images')->nullable()->comment('Imágenes');
            $table->boolean('is_active')->default(true)->comment('¿Activo?');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
