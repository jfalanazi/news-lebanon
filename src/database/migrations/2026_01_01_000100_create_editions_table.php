<?php
// جدول الأعداد (كل عدد = نشرة يوم واحد)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('editions', function (Blueprint $t) {
            $t->id();
            $t->unsignedInteger('issue_number')->unique();   // رقم العدد (تلقائي)
            $t->date('edition_date')->unique();              // عدد واحد لكل يوم
            $t->enum('status', ['draft', 'in_review', 'approved', 'published'])->default('draft');
            $t->string('quote')->nullable();                 // عبارة التذييل
            $t->string('caption_link')->nullable();          // رابط QR / تعليق واتساب
            $t->json('weather')->nullable();                 // لقطة الطقس وقت الاعتماد
            $t->json('prayers')->nullable();                 // لقطة مواقيت الصلاة
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('published_at')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('editions'); }
};
