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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->string('name')->index()->nullable();
            $table->string('email')->index()->nullable();
            $table->text('body');
            $table->boolean('is_approved')->default(false);
            $table->morphs('commentable');
            $table->foreignId('parent_id')->index()->nullable();
            $table->integer('depth')->default(0);
            $table->timestamps();
            $table->index(['commentable_id', 'commentable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
