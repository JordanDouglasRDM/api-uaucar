<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained();
            $table->foreignId('user_id')->constrained();

            // Classificação
            $table->string('tipo');
            $table->string('status')->default('disponivel');

            // Dados Principais (Filtros e Busca)
            $table->string('marca');
            $table->string('modelo');
            $table->year('ano_fabricacao');
            $table->year('ano_modelo');
            $table->string('placa')->unique()->nullable();
            $table->string('chassi')->unique()->nullable();
            $table->string('cor')->nullable();
            $table->unsignedInteger('quilometragem')->default(0);

            // Especificações Técnicas Genéricas
            $table->string('tipo_combustivel');
            $table->string('transmissao');

            // Valores Financeiros
            $table->decimal('preco_compra', 15, 2)->nullable();
            $table->decimal('preco_venda', 15, 2);
            $table->string('codigo_fipe')->nullable();
            $table->decimal('valor_fipe', 15, 2)->nullable();

            // Conteúdo e Marketing
            $table->text('descricao')->nullable();

            // Aqui guardamos: portas, cilindradas, eixos, capacidade de carga, etc.
            $table->json('atributos_adicionais')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
