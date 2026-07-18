<?php
// فعاليات لبنان — بتاريخ بداية/نهاية وخاصية البقاء في النشرة
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $t) {
            $t->id();
            $t->foreignId('edition_id')->nullable()->constrained()->cascadeOnDelete();
            $t->string('category')->nullable();              // ثقافي/سياحي/فني/رياضي/أخرى
            $t->string('title');
            $t->date('start_date')->nullable();
            $t->date('end_date')->nullable();                // فارغ = يوم واحد
            $t->unsignedSmallInteger('persist_days')->default(1); // يبقى X يوم في النشرة
            $t->unsignedSmallInteger('position')->default(0);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('events'); }
};
