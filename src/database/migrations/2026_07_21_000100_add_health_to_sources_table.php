<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->timestamp('last_fetched_at')->nullable();
            $table->unsignedInteger('last_fetch_count')->nullable();
            $table->string('last_error', 200)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->dropColumn(['last_fetched_at', 'last_fetch_count', 'last_error']);
        });
    }
};
