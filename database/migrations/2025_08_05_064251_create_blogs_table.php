<?php

use App\Models\Author;
use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete()->comment('Empresa');
            $table->foreignId('author_id')->constrained('authors')->cascadeOnDelete()->comment('Author');
            $table->string('title')->comment('Título');
            $table->string('slug')->unique()->comment('Slug');
            $table->mediumText('description')->nullable()->comment('Descripción');
            $table->mediumText('content')->comment('Contenido');
            $table->string('image')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
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
