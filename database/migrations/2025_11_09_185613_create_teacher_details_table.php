<?php

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
        Schema::create('teacher_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained();
            $table->foreignId('image_id')->nullable()->constrained('media');
            $table->string('academic_title',50)->nullable(); // عنوان علمی یا تخصصی
            $table->string('teaching',50)->nullable(); // زمینه تدریس
            $table->string('job_title',50)->nullable(); // عنوان شغلی
            $table->string('degree',50)->nullable(); // مدرک تحصیلی
            $table->text('history')->nullable(); // سوابق تدریس
            $table->text('skills')->nullable(); // فهرست مهارت ها و تخصص ها
            $table->text('bio')->nullable(); // بیوگرافی
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_details');
    }
};
