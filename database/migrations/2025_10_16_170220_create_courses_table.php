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
            $table->string('title');// عنوان
            $table->string('slug')->unique(); // اسلاگ
            $table->text('description'); // توضیحات
            $table->text('requirements')->nullable(); // نیازمندی ها
            $table->string('thumbnail')->nullable(); // تصویر شاخص
            $table->foreignId('teacher_id')->constrained('users');
            $table->foreignId('category_id')->constrained();
            $table->unsignedBigInteger('price')->default(0);
            $table->float('rate')->default(0); // میانگین امتیاز
            $table->boolean('must_complete_quizzes')->default(false);
            $table->enum('status', enumNames(CourseStatusEnum::cases()))->default('draft');
            $table->dateTime('published_at')->index();
            $table->timestamps();
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
