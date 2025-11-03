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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->nullable();
            $table->string('file_type')->index(); // image, video, audio, document
            $table->string('alt')->index();
            $table->string('file_name')->index();
            $table->string('mime_type')->nullable();
            $table->boolean('ssl');
            $table->string('domain')->comment('دامنه هاستی که فایل در آن آپلود شده است');
            $table->string('path'); // مسیر فایل
            $table->unsignedBigInteger('size')->nullable(); // حجم فایل
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
