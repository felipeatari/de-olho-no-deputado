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
        Schema::create('deputados', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('uri')->nullable(); // link da API do deputado
            $table->string('nome');
            $table->string('sigla_partido', 30)->nullable();
            $table->string('uri_partido')->nullable(); // link da API do partido
            $table->string('sigla_uf', 5)->nullable();
            $table->unsignedInteger('id_legislatura')->nullable();
            $table->string('url_foto')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deputados');
    }
};
