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
        Schema::create('videos', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // کاربر صاحب ویدیو (اختیاری اگر سیستم لاگین ندارید)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

            // وضعیت‌های ویدیو: recording, pending_process, completed, failed
            $table->string('status')->default('recording')->index();

            // مسیر فایل نهایی (بعد از اتمام پردازش پر می‌شود)
            $table->string('path')->nullable();

            // مسیر تصویر بندانگشتی (اختیاری - در جاب FFmpeg می‌توان ساخت)
            $table->string('thumbnail')->nullable();

            // اطلاعات اضافی برای آمار
            $table->integer('duration')->nullable(); // مدت زمان به ثانیه
            $table->bigInteger('size')->nullable(); // حجم به بایت

            $table->timestamps();
            $table->softDeletes(); // برای اینکه اگر کاربر پاک کرد، فایل واقعی حذف نشود (اختیاری)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
