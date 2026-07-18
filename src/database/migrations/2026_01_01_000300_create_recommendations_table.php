<?php
// توصيات اليوم — مطعم/معلم/منتزه/مقهى
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recommendations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('edition_id')->constrained()->cascadeOnDelete();
            $t->enum('type', ['restaurant', 'landmark', 'park', 'cafe'])->default('restaurant');
            $t->string('name');
            $t->string('description')->nullable();
            $t->string('area')->nullable();
            $t->unsignedSmallInteger('position')->default(0);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('recommendations'); }
};
