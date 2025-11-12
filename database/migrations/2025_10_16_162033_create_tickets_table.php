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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained()->cascadeOnDelete();
            //$table->string('title'); // عنوان تیکت
            $table->string('subject'); // موضوع تیکت
            $table->text('message'); // متن پیام
            $table->dateTime('read_at')->nullable()->index();
            //$table->enum('status', ['open', 'in_progress', 'closed'])->default('open')->index();
            //$table->enum('priority', ['low', 'medium', 'high'])->default('medium')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
