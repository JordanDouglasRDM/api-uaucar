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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();

            $table->string('domain', 100)->unique()->nullable(false);
            $table->string('name', 100);
            $table->string('status')->default('active');
            $table->string('cnpj', 14)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('administrator_email', 100)->nullable();
            $table->string('administrator_phone', 15)->nullable();
            $table->string('responsible_name', 100)->nullable();
            $table->string('responsible_email', 100)->nullable();
            $table->string('responsible_phone', 15)->nullable();
            $table->json('options')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
