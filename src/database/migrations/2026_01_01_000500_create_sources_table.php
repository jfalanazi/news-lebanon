<?php
// قائمة المصادر الرئيسية (لاقتراح المصادر ولاحقًا سحب RSS)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sources', function (Blueprint $t) {
            $t->id();
            $t->string('name');                  // النهار / الأخبار / LBCI ...
            $t->string('domain')->nullable();    // annahar.com
            $t->string('url')->nullable();       // الموقع أو رابط RSS لاحقًا
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('sources'); }
};
