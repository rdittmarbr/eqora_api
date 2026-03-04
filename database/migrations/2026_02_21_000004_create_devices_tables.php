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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->nullOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained('partners')->nullOnDelete();
            $table->string('device_uuid', 120)->unique();
            $table->string('device_type', 20);
            $table->string('device_name')->nullable();
            $table->string('name')->nullable();
            $table->string('platform', 40);
            $table->string('app_version', 40)->nullable();
            $table->string('browser_name', 80)->nullable();
            $table->string('browser_version', 80)->nullable();
            $table->json('specifications')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('connected_ip', 45)->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('connected_at')->nullable();
            $table->timestamp('disconnected_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_blocked')->default(false);
            $table->string('blocked_reason', 120)->nullable();
            $table->timestamp('blocked_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'partner_id']);
        });

        Schema::create('device_connection_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->nullOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained('partners')->nullOnDelete();
            $table->string('event', 40);
            $table->string('ip_address', 45)->nullable();
            $table->boolean('is_active')->default(false);
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['device_id', 'created_at']);
            $table->index(['tenant_id', 'partner_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_connection_logs');
        Schema::dropIfExists('devices');
    }
};
