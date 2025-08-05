<?php

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\TimeZone;
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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->unique()->comment('Nombre del negocio');
            $table->string('short',20)->unique()->comment('Nombre corto');
            $table->string('slug',100)->unique()->coment('Slug');
            $table->string('url_company',50)->unique()->comment('Url para la empresa');
            $table->string('rfc',13)->index()->comment('RFC');
            $table->string('email')->nullable()->default(null)->comment('Correo Electrónico');
            $table->string('phone',15)->nullable()->default(null)->comment('Teléfono');
            $table->string('address',80)->nullable()->default(null)->comment('Dirección calle');
            $table->string('num_ext',6)->nullable()->default(null)->comment('Número Exterior');
            $table->string('num_int',6)->nullable()->default(null)->comment('Número Interior');
            $table->foreignIdFor(Country::class)->default(env('APP_DEFAULT_COUNTRY_ID',142))->comment('País');
            $table->foreignIdFor(State::class)->default(env('APP_DEFAULT_STATE_ID',3455))->comment('Entiad Federativa');
            $table->foreignIdFor(City::class)->default(env('APP_DEFAULT_CITY_ID',73025))->comment('Entiad Federativa');
            $table->string('municipality',100)->nullable()->default(null)->comment('Municipio');
            $table->string('city',100)->nullable()->default(null)->comment('Ciudad');
            $table->string('colony',100)->nullable()->default(null)->comment('Colonia');
            $table->string('zipcode',5)->nullable()->default(null)->comment('Código Postal');
            $table->string('logo')->nullable()->default(null)->comment('Logotipo');
            $table->boolean('active')->default(0)->comment('¿Está activa?');
            $table->foreignIdFor(User::class)->comment('Usuario que creó la empresa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
