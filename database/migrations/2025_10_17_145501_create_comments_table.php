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
            $table->text('body');
            $table->boolean('is_approved')->default(false);
            
            // Polymorphic relationship
            $table->unsignedBigInteger('commentable_id');
            $table->string('commentable_type');
            
            // User who created the comment
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // For nested comments (replies)
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('depth')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['commentable_id', 'commentable_type']);
            $table->index('parent_id');
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
