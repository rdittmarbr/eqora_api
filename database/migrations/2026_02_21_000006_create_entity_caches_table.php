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
        Schema::create('entity_caches', function (Blueprint $table) {
            $table->id();
            $table->string('document', 20);
            $table->foreignId('partner_id')->nullable()->constrained('partners')->nullOnDelete();
            $table->string('source', 80)->nullable();
            $table->json('payload');
            $table->timestamp('fetched_at')->nullable();
            $table->timestamps();

            $table->unique(['document', 'partner_id']);
            $table->index(['partner_id', 'fetched_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_caches');
    }
};
