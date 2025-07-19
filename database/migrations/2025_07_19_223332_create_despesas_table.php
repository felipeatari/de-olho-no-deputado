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
        Schema::create('despesas', function (Blueprint $table) {
            $table->id(); // id interno autoincrement
            $table->unsignedBigInteger('deputado_id');
            $table->integer('ano');
            $table->integer('mes');
            $table->string('tipo_despesa')->nullable();
            $table->unsignedBigInteger('cod_documento')->nullable();
            $table->string('tipo_documento')->nullable();
            $table->date('data_documento')->nullable();
            $table->string('num_documento')->nullable();
            $table->decimal('valor_documento', 10, 2)->nullable();
            $table->decimal('valor_glosa', 10, 2)->nullable();
            $table->decimal('valor_liquido', 10, 2)->nullable();
            $table->string('nome_fornecedor')->nullable();
            $table->string('cnpj_cpf_fornecedor', 20)->nullable();
            $table->integer('parcela')->nullable();
            $table->text('url_documento')->nullable();
            $table->timestamp('data_cadastro')->useCurrent();

            $table->foreign('deputado_id')->references('id')->on('deputados')->onDelete('cascade');

            // Evita duplicatas por deputado + documento
            $table->unique(['deputado_id', 'cod_documento']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despesas');
    }
};
