<?php

use App\Enums\CourseStatusEnum;
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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('title');// عنوان
            $table->string('slug')->unique(); // اسلاگ
            $table->text('summary')->fulltext(); // خلاصه
            $table->text('description')->fulltext(); // توضیحات
            $table->foreignId('thumbnail_id')->nullable()->constrained('media'); // تصویر شاخص
            $table->text('intro_url')->nullable(); // آدرس کامل ویدیو اینترو
            $table->string('intro_filename')->nullable(); // نام فایل ویدیو اینترو
            $table->foreignId('poster_id')->nullable()->constrained('media'); // تصویر شاخص
            $table->foreignId('teacher_id')->nullable()->constrained('users');
            $table->unsignedBigInteger('price')->nullable()->default(0);
            $table->float('rate')->nullable()->default(0); // میانگین امتیاز
            $table->boolean('must_complete_quizzes')->default(false);
            $table->enum('status', enumNames(CourseStatusEnum::cases()))->default('draft');
            $table->integer('views')->index()->default(0);
            $table->dateTime('published_at')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
