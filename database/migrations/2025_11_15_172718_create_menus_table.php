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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();

            $table->string('title');                // عنوان منو
            $table->string('icon')->nullable();     // آیکون (مثلاً کلاس FontAwesome)
            $table->string('url')->nullable();      // لینک مقصد
            $table->unsignedBigInteger('parent_id')->nullable(); // والد منو برای ساختار تو در تو
            $table->integer('order')->default(0);   // ترتیب نمایش
            $table->boolean('is_active')->default(true); // فعال بودن منو
            $table->string('target')->default('_self');  // target لینک (_blank, _self)
            $table->string('type')->nullable();     // نوع منو (مثلاً header, footer, sidebar)
            $table->json('meta')->nullable();       // اطلاعات اضافی برای توسعه بعدی (json)

            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
