<?php
// مجموعة الأخبار المرشّحة (تملؤها لاحقًا مهمة RSS + Claude في المرحلة 4)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('news_candidates', function (Blueprint $t) {
            $t->id();
            $t->date('for_date')->index();
            $t->string('category')->nullable();
            $t->string('url')->nullable();
            $t->string('source_name')->nullable();
            $t->string('title');
            $t->string('excerpt', 500)->nullable();
            $t->boolean('used')->default(false);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('news_candidates'); }
};
