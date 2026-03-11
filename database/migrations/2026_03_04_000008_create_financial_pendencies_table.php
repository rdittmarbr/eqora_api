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
        Schema::create('financial_pendencies', function (Blueprint $table) {
            $table->string('id', 40)->primary();
            $table->string('citizen_document', 20)->nullable();
            $table->string('property_registration', 40)->nullable();
            $table->string('description');
            $table->date('due_date');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('BRL');
            $table->string('status', 20)->default('open');
            $table->timestamps();

            $table->index('citizen_document');
            $table->index('property_registration');
            $table->index('status');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_pendencies');
    }
};
