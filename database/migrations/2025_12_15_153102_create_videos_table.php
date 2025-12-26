<?php

use App\Enums\VideoStatusEnum;
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
            $table->id();
            $table->uuid()->index();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('cascade');
            $table->foreignId('quiz_id')->nullable()->constrained('quizzes')->onDelete('cascade');
            $table->enum('status',enumNames(VideoStatusEnum::cases()))->default('pending')->index();
            $table->string('path')->nullable();
            $table->string('thumbnail')->nullable();
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
