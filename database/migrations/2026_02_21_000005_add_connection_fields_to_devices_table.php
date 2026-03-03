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
        Schema::table('devices', function (Blueprint $table) {
            $table->string('connected_ip', 45)->nullable()->after('ip_address');
            $table->timestamp('connected_at')->nullable()->after('last_seen_at');
            $table->timestamp('disconnected_at')->nullable()->after('connected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn(['connected_ip', 'connected_at', 'disconnected_at']);
        });
    }
};
