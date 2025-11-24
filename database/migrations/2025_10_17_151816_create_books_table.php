<?php

use App\Enums\BookStatusEnum;
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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title',150);
            $table->string('subtitle')->index()->nullable();
            $table->string('slug')->index()->unique();
            $table->text('summary')->nullable();
            $table->text('content')->nullable();
            $table->foreignId('thumbnail_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('publisher',100)->nullable();
            $table->decimal('price', 20, 0)->index()->default(0);
            $table->decimal('special_price', 20, 0)->nullable()->index();
            $table->boolean('is_stock')->index()->default(true);
            $table->unsignedInteger('stock')->index()->nullable();
            $table->unsignedInteger('max_order')->index()->default(1);
            $table->string('year_published',30)->nullable();
            $table->string('edition',50)->nullable();
            $table->string('size',50)->nullable();
            $table->string('author',100)->index()->nullable();
            $table->tinyInteger('rate')->index()->nullable();
            $table->enum('status', enumNames(BookStatusEnum::cases()))->index()->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->fullText(['title', 'subtitle', 'content']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
