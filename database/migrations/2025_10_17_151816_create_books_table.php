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
            $table->text('expert')->nullable();
            $table->text('content');
            $table->string('thumbnail')->nullable();
            $table->string('publisher',100)->nullable();
            $table->decimal('price', 20, 0)->index()->default(0);
            $table->decimal('special_price', 20, 0)->index()->default(0);
            $table->unsignedInteger('stock')->index()->default(0);
            $table->unsignedInteger('max_order')->index()->default(1);
            $table->string('year_published',30);
            $table->string('edition',50);
            $table->enum('status', enumNames(BookStatusEnum::cases()))->index()->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->fullText(['title', 'content']);
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
