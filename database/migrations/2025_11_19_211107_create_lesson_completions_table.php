<?php

use App\Enums\LessonCompletionStatusEnum;
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
        Schema::create('lesson_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_lesson_id')->constrained()->cascadeOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedTinyInteger('progress')->default(0); // 0-100
            $table->enum('status', enumNames(LessonCompletionStatusEnum::cases()))->default('notStarted');
            $table->timestamps();

            // Ensure a user can only have one completion record per lesson
            $table->unique(['user_id', 'course_lesson_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_completions');
    }
};
