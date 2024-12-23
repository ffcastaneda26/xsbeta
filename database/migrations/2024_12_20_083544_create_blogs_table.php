<?php

use App\Models\Category;
use App\Models\Type;
use App\Models\User;
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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Título');
            $table->string('slug')->comment('Slug');
            $table->string('subtitle')->nullable()->default(null)->comment('Subtítulo');
            $table->foreignIdFor(Type::class)->comment('Tipo');
            $table->foreignIdFor(Category::class)->comment('Categoría');
            $table->timestamp('date')->comment('Fecha');
            $table->unsignedBigInteger('author_id')->comment('Autor');
            $table->mediumText('introduction')->nullable()->default(null)->comment('Introducción');
            $table->mediumText('description')->nullable()->default(null)->comment('Descripción');
            $table->boolean('active')->default(0)->comment('¿Activo?');
            $table->string('image')->nullable()->default(null)->comment('Imagen');
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            // Llave de autor a usuarios
            $table->foreign('author_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
