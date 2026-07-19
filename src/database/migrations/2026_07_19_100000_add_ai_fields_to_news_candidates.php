<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news_candidates', function (Blueprint $table) {
            $table->string('priority')->default('normal')->after('category');
            $table->boolean('ai_processed')->default(false)->after('used');
        });
    }

    public function down(): void
    {
        Schema::table('news_candidates', function (Blueprint $table) {
            $table->dropColumn(['priority', 'ai_processed']);
        });
    }
};
