<?php

use App\Enums\DegreeEnum;
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
        Schema::create('educationals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('institution'); // نام موسسه یا دانشگاه
            $table->string('field_of_study'); // رشته تحصیلی
            $table->enum('degree', enumNames(DegreeEnum::cases())); // مقطع تحصیلی
            $table->date('start_date'); // تاریخ شروع
            $table->date('end_date')->nullable(); // تاریخ پایان (اختیاری)
            $table->boolean('is_currently_studying')->default(false); // در حال تحصیل
            $table->text('description')->nullable(); // توضیحات اضافی
            $table->timestamps();

            // ایندکس‌گذاری برای جستجوی سریع‌تر
            $table->index(['user_id', 'degree']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educational');
    }
};
