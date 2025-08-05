<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('iso2', 2)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->string('iso3', 3)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->string('numeric_code', 3)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('phonecode')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('capital')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('currency')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('currency_name')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('currency_symbol')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('tld')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('native')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('region')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('subregion')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->text('timezones')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->text('translations')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('emoji', 191)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('emojiU', 191)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->boolean('flag')->default(true);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('states', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('country_id');
            $table->string('name', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();

            // Llave foránea
            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('state_id');
            $table->string('name', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();

            // Llaves foráneas
            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('state_id')
                ->references('id')
                ->on('states')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');

        Schema::dropIfExists('countries');
    }
};
