<?php
// أخبار العدد — التركيبة: تصنيف/مصدر(من الرابط)/عنوان/نبذة/أولوية
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('news_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('edition_id')->constrained()->cascadeOnDelete();
            $t->string('category')->nullable();              // التصنيف
            $t->string('url')->nullable();                   // رابط الخبر (يُشتق منه المصدر)
            $t->string('source_name')->nullable();           // اسم المصدر (مخزّن بعد الاشتقاق)
            $t->string('title');                             // العنوان
            $t->string('excerpt', 500)->nullable();          // النبذة (سطران)
            $t->enum('priority', ['normal', 'important', 'breaking'])->default('normal'); // عادي/مهم/عاجل
            $t->unsignedSmallInteger('position')->default(0);// ترتيب الظهور
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('news_items'); }
};
