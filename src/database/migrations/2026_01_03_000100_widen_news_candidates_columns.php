<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('news_candidates', function (Blueprint $t) {
            $t->text('url')->nullable()->change();
        });
        Schema::table('news_items', function (Blueprint $t) {
            $t->text('url')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('news_candidates', function (Blueprint $t) {
            $t->string('url')->nullable()->change();
        });
        Schema::table('news_items', function (Blueprint $t) {
            $t->string('url')->nullable()->change();
        });
    }
};
